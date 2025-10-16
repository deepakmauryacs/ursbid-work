<?php

namespace App\Http\Controllers\URSController;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingAcceptedBiddingController extends Controller
{
    public function index(Request $request)
    {
        $seller = $request->session()->get('seller');

        if (! $seller) {
            abort(403, 'Seller session not found.');
        }

        $perPage = (int) $request->input('r_page', 25);

        $filters = [
            'category' => $request->input('category'),
            'date' => $request->input('date'),
            'city' => $request->input('city'),
            'quantity' => $request->input('quantity'),
            'product_name' => $request->input('product_name'),
            'qutation_id' => $request->input('qutation_id'),
            'r_page' => $perPage,
        ];

        $query = $this->baseAcceptedBiddingQuery($seller->email);

        if ($request->filled('category')) {
            $query->where('c.id', $request->input('category'));
        }

        if ($request->filled('date')) {
            $date = $this->parseDate($request->input('date'));
            if ($date) {
                $query->whereDate('qutation_form.date_time', $date);
            }
        }

        if ($request->filled('city')) {
            $query->where('qutation_form.city', 'like', '%' . $request->input('city') . '%');
        }

        if ($request->filled('quantity')) {
            $query->where('qutation_form.quantity', 'like', '%' . $request->input('quantity') . '%');
        }

        if ($request->filled('product_name')) {
            $productName = $request->input('product_name');
            $query->where(function ($innerQuery) use ($productName) {
                $innerQuery
                    ->where('product.title', 'like', '%' . $productName . '%')
                    ->orWhere('bidding_price.product_name', 'like', '%' . $productName . '%');
            });
        }

        if ($request->filled('qutation_id')) {
            $query->where('qutation_form.qutation_id', 'like', '%' . $request->input('qutation_id') . '%');
        }

        $records = $query
            ->orderByDesc('bidding_price.id')
            ->paginate($perPage)
            ->withQueryString();

        $records = $this->appendComputedState($records);

        $categoryData = DB::table('categories')
            ->select('id', 'name', DB::raw('name as title'))
            ->orderBy('name')
            ->get();

        if ($request->ajax()) {
            return view('ursdashboard.accounting.accepted-bidding.partials.table', [
                'records' => $records,
                'filters' => $filters,
            ])->render();
        }

        return view('ursdashboard.accounting.accepted-bidding.list', [
            'seller' => $seller,
            'records' => $records,
            'filters' => $filters,
            'category_data' => $categoryData,
        ]);
    }

    protected function baseAcceptedBiddingQuery(string $sellerEmail)
    {
        $productBrands = DB::table('product_brands')
            ->select('product_id', DB::raw('MAX(brand_name) as brand_name'))
            ->groupBy('product_id');

        return DB::table('bidding_price')
            ->leftJoin('seller', 'bidding_price.user_email', '=', 'seller.email')
            ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')
            ->leftJoinSub($productBrands, 'pb', function ($join) {
                $join->on('product.id', '=', 'pb.product_id');
            })
            ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->where('bidding_price.seller_email', $sellerEmail)
            ->where('bidding_price.payment_status', 'success')
            ->where(function ($query) {
                $query->whereIn('bidding_price.action', [1, '1', 'accepted', 'success', 'completed']);
            })
            ->select(
                'bidding_price.id as bidding_id',
                'bidding_price.price as platform_fee',
                'bidding_price.action as action',
                'bidding_price.product_name as requested_product_name',
                'bidding_price.data_id as qutation_form_id',
                'bidding_price.rate as rate',
                'qutation_form.qutation_id as qutation_id',
                'qutation_form.name as name',
                'qutation_form.date_time as date_time',
                'qutation_form.quantity as quantity',
                'qutation_form.unit as unit',
                'qutation_form.city as city',
                'qutation_form.status as qutation_status',
                'product.title as product_name',
                'product.image as product_image',
                'pb.brand_name as product_brand',
                'sc.name as sub_name',
                'c.name as category_name'
            );
    }

    protected function appendComputedState(LengthAwarePaginator $records): LengthAwarePaginator
    {
        $transformed = $records->getCollection()->map(function ($record) {
            $record = (object) $record;

            $record->status_badge = $this->resolveStatusBadge($record->action ?? null);
            $record->quantity_numeric = $this->extractNumericQuantity($record->quantity ?? null);

            if (isset($record->rate) && $record->rate !== null) {
                $record->total_price = $record->quantity_numeric * (float) $record->rate;
            } else {
                $record->total_price = null;
            }

            return $record;
        });

        $records->setCollection($transformed);

        return $records;
    }

    protected function resolveStatusBadge($action): string
    {
        return match ($action) {
            1, '1', 'accepted', 'success', 'completed' => 'success',
            'pending' => 'warning',
            'rejected', 'cancelled', 'canceled' => 'danger',
            default => 'secondary',
        };
    }

    protected function extractNumericQuantity($quantity): float
    {
        if (is_numeric($quantity)) {
            return (float) $quantity;
        }

        if (is_string($quantity) && preg_match('/\d+(?:\.\d+)?/', $quantity, $matches)) {
            return (float) ($matches[0] ?? 0);
        }

        return 0.0;
    }

    protected function parseDate(?string $date): ?string
    {
        if (! $date) {
            return null;
        }

        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $exception) {
            return null;
        }
    }
}
