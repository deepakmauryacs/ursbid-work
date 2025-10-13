<?php

namespace App\Http\Controllers\URSController;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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

        return view('ursdashboard.price-list.show', [
            'seller' => $seller,
            'records' => $records,
            'total' => $records->count(),
            'enquiryId' => $dataId,
        ]);
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
            $record->formatted_date = $record->date_time
                ? Carbon::parse($record->date_time)->format('Y-m-d')
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
