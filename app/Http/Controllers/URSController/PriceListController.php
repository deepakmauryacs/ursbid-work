<?php

namespace App\Http\Controllers\URSController;

use App\Http\Controllers\Controller;
use App\Support\SimpleXlsxExporter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PriceListController extends Controller
{
    public function show(Request $request, int $dataId)
    {
        $seller = $request->session()->get('seller');

        if (!$seller) {
            abort(403, 'Seller session not found.');
        }

        $records = $this->basePriceListQuery($seller->email, $dataId)
            ->orderByDesc('bidding_price.id')
            ->get();

        $records = $this->appendComputedState($records);

        $firstRecord = $records->first();
        $cisQuotationId = $firstRecord->qutation_id ?? null;

        return view('ursdashboard.price-list.show', [
            'seller'          => $seller,
            'records'         => $records,
            'total'           => $records->count(),
            'enquiryId'       => $dataId,
            'cisQuotationId'  => $cisQuotationId,
        ]);
    }

    public function cis(Request $request, string $quotationId)
    {
        $seller = $request->session()->get('seller');

        if (!$seller) {
            abort(403, 'Seller session not found.');
        }

        $quotationForm = DB::table('qutation_form')
            ->where('qutation_id', $quotationId)
            ->first();

        abort_if(!$quotationForm, 404);

        $invitedSellers = $this->fetchInvitedSellers($quotationForm);

        $records = $this->basePriceListQuery($seller->email, (int) $quotationForm->id)
            ->orderByDesc('bidding_price.id')
            ->get();

        $records = $this->mergeInvitedSellers($records, $invitedSellers, $quotationForm);
        $records = $this->appendComputedState($records);

        $enquiry = $this->hydrateEnquiryContext($records->first(), $quotationForm);

        return view('ursdashboard.price-list.cis', [
            'seller'          => $seller,
            'records'         => $records,
            'dataId'          => (int) $quotationForm->id,
            'enquiry'         => $enquiry,
            'invitedSellers'  => $invitedSellers,
            'quotationForm'   => $quotationForm,
        ]);
    }

    public function exportCis(Request $request, string $quotationId)
    {
        $seller = $request->session()->get('seller');

        if (!$seller) {
            abort(403, 'Seller session not found.');
        }

        $quotationForm = DB::table('qutation_form')
            ->where('qutation_id', $quotationId)
            ->first();

        abort_if(!$quotationForm, 404);

        $invitedSellers = $this->fetchInvitedSellers($quotationForm);

        $records = $this->basePriceListQuery($seller->email, (int) $quotationForm->id)
            ->orderByDesc('bidding_price.id')
            ->get();

        $records = $this->mergeInvitedSellers($records, $invitedSellers, $quotationForm);
        $records = $this->appendComputedState($records);

        $enquiry = $this->hydrateEnquiryContext($records->first(), $quotationForm);

        $sellerRecords = $records
            ->filter(fn ($record) => is_numeric($record->rate))
            ->sortBy(fn ($record) => (float) $record->rate)
            ->values();

        $productTitle = collect([
                $enquiry->product_title ?? null,
                $enquiry->bidding_price_product_name ?? null,
                $enquiry->product_name ?? null,
            ])
            ->filter(fn ($value) => filled($value))
            ->first() ?? '-';

        $productBrand = collect([
                $enquiry->product_brand ?? null,
                $enquiry->material ?? null,
            ])
            ->filter(fn ($value) => filled($value))
            ->first();

        $quantityValue = collect([
                $enquiry->quantity ?? null,
            ])->filter(fn ($value) => filled($value))->first();

        $unitValue = collect([
                $enquiry->unit ?? null,
            ])->filter(fn ($value) => filled($value))->first();

        $quantityLabel = $quantityValue ?? '-';
        if (filled($unitValue)) {
            $quantityLabel = trim((string) $quantityLabel . ' ' . $unitValue);
        }

        $startPrice = $sellerRecords
            ->pluck('rate')
            ->filter(fn ($rate) => is_numeric($rate))
            ->map(fn ($rate) => (float) $rate)
            ->min();

        $quantityNumeric = null;
        if (is_numeric($quantityValue)) {
            $quantityNumeric = (float) $quantityValue;
        } elseif (is_string($quantityValue)) {
            preg_match('/\d+(?:\.\d+)?/', $quantityValue, $matches);
            $quantityNumeric = isset($matches[0]) ? (float) $matches[0] : null;
        }

        $totalAmount = null;
        if (is_numeric($quantityNumeric) && is_numeric($startPrice)) {
            $totalAmount = (float) $quantityNumeric * (float) $startPrice;
        }

        $buyerName = $enquiry->name ?? $enquiry->buyer_name ?? 'N/A';
        $quotationDateRaw = $enquiry->date_time ?? $enquiry->created_at ?? null;

        try {
            $quotationDateLabel = $quotationDateRaw
                ? Carbon::parse($quotationDateRaw)->format('d/m/Y')
                : 'N/A';
        } catch (\Exception $e) {
            $quotationDateLabel = is_string($quotationDateRaw) ? $quotationDateRaw : 'N/A';
        }

        $formatCurrency = function ($value) {
            return is_numeric($value)
                ? '₹ ' . number_format((float) $value, 2)
                : '-';
        };

        $vendorNames = $sellerRecords->map(function ($record) {
            $name = $record->seller_name ?? 'Vendor';
            $phone = $record->seller_phone ?? '-';

            return trim($name . ' (M: ' . $phone . ')');
        });

        $vendorRates = $sellerRecords->map(function ($record) use ($formatCurrency) {
            return $formatCurrency($record->rate ?? null);
        });

        $vendorTotals = $sellerRecords->map(function ($record) use ($formatCurrency) {
            $total = $record->calculated_total ?? null;

            if (! is_numeric($total)) {
                $quantity = $record->numeric_quantity ?? null;
                if (is_numeric($quantity) && is_numeric($record->rate ?? null)) {
                    $total = (float) $quantity * (float) $record->rate;
                }
            }

            return $formatCurrency($total);
        });

        $rows = [];
        $rows[] = ['Comparative Information Statement'];
        $rows[] = [];
        $rows[] = ['Quotation ID', $enquiry->qutation_id ?? $enquiry->enquiry_id ?? '-', '', 'Date', $quotationDateLabel];
        $rows[] = ['Buyer / Client', $buyerName];
        $rows[] = [];
        $rows[] = ['Product Details'];
        $rows[] = ['Product', 'Specs', 'Quantity/UOM', 'Start Price'];
        $rows[] = [
            filled($productTitle) ? $productTitle : '-',
            filled($productBrand) ? $productBrand : '-',
            filled($quantityLabel) ? $quantityLabel : '-',
            is_numeric($startPrice) ? $formatCurrency($startPrice) : '-',
        ];
        $rows[] = ['Total Amount', '', '', $formatCurrency($totalAmount)];
        $rows[] = [];

        if ($sellerRecords->isEmpty()) {
            $rows[] = ['Vendor', 'No vendor submitted a price yet.'];
            $rows[] = ['Rate Per Unit (₹)', '-'];
            $rows[] = ['Total Amount (₹)', '-'];
        } else {
            $rows[] = array_merge(['Vendor'], $vendorNames->all());
            $rows[] = array_merge(['Rate Per Unit (₹)'], $vendorRates->all());
            $rows[] = array_merge(['Total Amount (₹)'], $vendorTotals->all());
        }

        $rows[] = [];
        $rows[] = ['Remarks (Message)', filled($enquiry->qutation_form_message ?? null) ? $enquiry->qutation_form_message : '-'];
        $rows[] = ['Material', filled($enquiry->material ?? null) ? $enquiry->material : '-'];
        $rows[] = ['Delivery Period (In Days)', filled($enquiry->bid_time ?? null) ? $enquiry->bid_time : '-'];

        $sheetName = 'CIS';

        $xlsxContents = SimpleXlsxExporter::build($rows, $sheetName);

        $fileBase = $enquiry->qutation_id ?? $quotationId ?? 'cis-export';
        $fileSlug = Str::slug((string) $fileBase, '-');
        if ($fileSlug === '') {
            $fileSlug = 'cis-export';
        }

        $fileName = $fileSlug . '-' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($xlsxContents) {
            echo $xlsxContents;
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    protected function fetchInvitedSellers(object $quotationForm): Collection
    {
        $sellerIds = collect(explode(',', (string) ($quotationForm->seller_id ?? '')))
            ->map(fn (string $value) => (int) trim($value))
            ->filter(fn (int $id) => $id > 0)
            ->unique()
            ->values();

        if ($sellerIds->isEmpty()) {
            return collect();
        }

        $sellers = DB::table('seller')
            ->whereIn('id', $sellerIds)
            ->select('id', 'name', 'email', 'phone', 'hash_id')
            ->get()
            ->keyBy('id');

        return $sellerIds
            ->map(fn (int $id) => $sellers->get($id))
            ->filter();
    }

    protected function mergeInvitedSellers(Collection $records, Collection $invitedSellers, object $quotationForm): Collection
    {
        if ($invitedSellers->isEmpty()) {
            return $records;
        }

        $recordsBySellerId = $records->keyBy(fn ($record) => (int) ($record->seller_id ?? 0));
        $merged = collect();

        foreach ($invitedSellers as $seller) {
            if (!$seller) {
                continue;
            }

            $sellerId = (int) ($seller->id ?? 0);

            if ($sellerId && $recordsBySellerId->has($sellerId)) {
                $merged->push($recordsBySellerId->get($sellerId));
                continue;
            }

            $merged->push((object) [
                'seller_id'                     => $sellerId,
                'seller_name'                   => $seller->name ?? null,
                'seller_email'                  => $seller->email ?? null,
                'seller_phone'                  => $seller->phone ?? null,
                'seller_hash_id'                => $seller->hash_id ?? null,
                'product_title'                 => $quotationForm->product_title ?? null,
                'bidding_price_product_name'    => $quotationForm->product_title ?? null,
                'product_brand'                 => $quotationForm->material ?? null,
                'material'                      => $quotationForm->material ?? null,
                'quantity'                      => $quotationForm->quantity ?? null,
                'unit'                          => $quotationForm->unit ?? null,
                'rate'                          => null,
                'platform_fee'                  => null,
                'action'                        => null,
                'bid_time'                      => $quotationForm->bid_time ?? null,
                'qutation_form_message'         => $quotationForm->message ?? null,
                'qutation_id'                   => $quotationForm->qutation_id ?? null,
            ]);
        }

        $existingSellerIds = $merged
            ->map(fn ($record) => (int) ($record->seller_id ?? 0))
            ->filter()
            ->all();

        $remaining = $records->filter(function ($record) use ($existingSellerIds) {
            $sellerId = (int) ($record->seller_id ?? 0);

            return $sellerId === 0 || !in_array($sellerId, $existingSellerIds, true);
        });

        return $merged->merge($remaining)->values();
    }

    protected function hydrateEnquiryContext(?object $record, object $quotationForm): object
    {
        $enquiry = $record ? clone $record : (object) [];

        foreach ((array) $quotationForm as $key => $value) {
            if (!property_exists($enquiry, $key) || $enquiry->{$key} === null) {
                $enquiry->{$key} = $value;
            }
        }

        if (!property_exists($enquiry, 'qutation_id')) {
            $enquiry->qutation_id = $quotationForm->qutation_id ?? null;
        }

        return $enquiry;
    }

    protected function basePriceListQuery(string $buyerEmail, int $dataId)
    {
        $productBrands = DB::table('product_brands')
            ->select('product_id', DB::raw('MAX(brand_name) as brand_name'))
            ->groupBy('product_id');

        return DB::table('bidding_price')
            ->leftJoin('seller', 'bidding_price.seller_email', '=', 'seller.email')
            ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')
            ->leftJoinSub($productBrands, 'pb', function ($join) {
                $join->on('product.id', '=', 'pb.product_id');
            })
            ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->where('bidding_price.user_email', $buyerEmail)
            ->where('bidding_price.data_id', $dataId)
            ->where('bidding_price.hide', '0')
            ->where('bidding_price.payment_status', 'success')
            ->select(
                'bidding_price.id as bidding_price_id',
                'bidding_price.price as platform_fee',
                'bidding_price.action as action',
                'bidding_price.data_id as data_id',
                'bidding_price.rate as rate',
                'bidding_price.seller_email as seller_email',
                'bidding_price.product_id as bidding_price_product_id',
                'bidding_price.product_name as bidding_price_product_name',
                'bidding_price.filename as bidding_price_filename',

                'qutation_form.bid_time as bid_time',
                'qutation_form.material as material',
                'qutation_form.id as id',
                'qutation_form.name as name',
                'qutation_form.email as email',
                'qutation_form.product_id as qutation_form_product_id',
                'qutation_form.product_img as qutation_form_product_img',
                'qutation_form.message as qutation_form_message',
                'qutation_form.address as qutation_form_address',
                'qutation_form.zipcode as qutation_form_zipcode',
                'qutation_form.state as qutation_form_state',
                'qutation_form.city as qutation_form_city',
                'qutation_form.date_time as date_time',
                'qutation_form.image as qutation_form_image',
                'qutation_form.latitude as qutation_form_latitude',
                'qutation_form.longitude as qutation_form_longitude',
                'qutation_form.seller_id as qutation_form_seller_id',
                'qutation_form.unit as unit',
                'qutation_form.quantity as quantity',
                'qutation_form.status as qutation_form_status',
                'qutation_form.qutation_id as qutation_id',

                'seller.id as seller_id',
                'seller.name as seller_name',
                'seller.phone as seller_phone',
                'seller.hash_id as seller_hash_id',

                'product.id as product_id',
                'product.title as product_title',
                'product.image as product_image',
                'product.description as product_description',

                'pb.brand_name as product_brand',

                'sc.id as sub_id',
                'sc.name as sub_name',

                'c.id as category_id',
                'c.name as category_name'
            );
    }

    protected function appendComputedState(Collection $records): Collection
    {
        return $records->map(function ($record) {
            $dateTime = property_exists($record, 'date_time') ? $record->date_time : null;

            $record->formatted_date = $dateTime
                ? Carbon::parse($dateTime)->format('Y-m-d')
                : null;

            $quantityRaw = (string) ($record->quantity ?? '0');
            preg_match('/\d+(?:\.\d+)?/', $quantityRaw, $matches);
            $numericQuantity = isset($matches[0]) ? (float) $matches[0] : 0.0;
            $record->numeric_quantity = $numericQuantity;
            $record->calculated_total = $numericQuantity * (float) ($record->rate ?? 0);

            $record->status_badge = match ((string) ($record->action ?? '')) {
                '1' => 'accepted',
                '2' => 'rejected',
                default => 'pending',
            };

            $record->can_accept = (string) ($record->action ?? '') === '0';

            return $record;
        });
    }
}
