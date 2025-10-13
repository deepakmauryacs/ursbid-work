<?php

namespace App\Http\Controllers\URSController;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyBiddingController extends Controller
{
    public function index(Request $request)
    {
        $seller = $request->session()->get('seller');
        if (!$seller) {
            abort(403, 'Seller session not found.');
        }

        // Per-page guard (10–100)
        $perPage = (int) ($request->input('r_page', 25));
        $perPage = max(10, min(100, $perPage));

        // Gather filters (trimmed)
        $filters = [
            'keyword'      => trim((string) $request->input('keyword', '')),
            'category'     => trim((string) $request->input('category', '')), // category id
            'date'         => trim((string) $request->input('date', '')),
            'city'         => trim((string) $request->input('city', '')),
            'quantity'     => trim((string) $request->input('quantity', '')),
            'product_name' => trim((string) $request->input('product_name', '')),
            'qutation_id'  => trim((string) $request->input('qutation_id', '')),
            'r_page'       => $perPage,
        ];

        $query = $this->baseMyBiddingQuery($seller->email);

        // Filters
        if ($filters['category'] !== '') {
            $category = $filters['category'];
            $query->where(function ($innerQuery) use ($category) {
                // Match by categories.id OR by qutation_form.cat_id (CSV/in-list text)
                $innerQuery->where('c.id', $category)
                    ->orWhere('qutation_form.cat_id', 'like', '%' . $category . '%');
            });
        }

        if ($filters['date'] !== '') {
            $query->where('qutation_form.date_time', 'like', '%' . $filters['date'] . '%');
        }

        if ($filters['city'] !== '') {
            $query->where('qutation_form.city', 'like', '%' . $filters['city'] . '%');
        }

        if ($filters['quantity'] !== '') {
            $query->where('qutation_form.quantity', 'like', '%' . $filters['quantity'] . '%');
        }

        if ($filters['product_name'] !== '') {
            $productName = $filters['product_name'];
            $query->where(function ($innerQuery) use ($productName) {
                $innerQuery->where('qutation_form.product_name', 'like', '%' . $productName . '%')
                    ->orWhere('product.title', 'like', '%' . $productName . '%');
            });
        }

        if ($filters['qutation_id'] !== '') {
            $query->where('qutation_form.qutation_id', 'like', '%' . $filters['qutation_id'] . '%');
        }

        if ($filters['keyword'] !== '') {
            $keyword = $filters['keyword'];
            $query->where(function ($innerQuery) use ($keyword) {
                $innerQuery->where('qutation_form.product_name', 'like', '%' . $keyword . '%')
                    ->orWhere('qutation_form.name', 'like', '%' . $keyword . '%')
                    ->orWhere('qutation_form.city', 'like', '%' . $keyword . '%');
            });
        }

        // Pagination
        $records = $query
            ->orderByDesc('qutation_form.id')
            ->paginate($perPage)
            ->withQueryString();

        $records = $this->appendComputedState($records);

        // Category dropdown (active only)
        $categoryData = DB::table('categories')
            ->select('id', 'name', DB::raw('name as title'))
            ->where('status', '1')
            ->orderBy('name')
            ->get();

        if ($request->ajax()) {
            return view('ursdashboard.my-bidding.partials.table', [
                'records' => $records,
                'filters' => $filters,
            ])->render();
        }

        // echo "<pre>";
        // print_r($records); die();

        return view('ursdashboard.my-bidding.list', [
            'seller'        => $seller,
            'filters'       => $filters,
            'category_data' => $categoryData,
            'records'       => $records,
            // legacy keys some views expect:
            'datas'         => $filters,
            'data'          => $records,
            'total'         => $records->total(),
        ]);
    }

    /**
     * Base query for My Bidding – wired to new categories & sub_categories schemas.
     */
    protected function baseMyBiddingQuery(string $sellerEmail)
    {
        // Collapse multiple brand rows per product (matches your original logic)
        $productBrands = DB::table('product_brands')
            ->select('product_id', DB::raw('MAX(brand_name) as brand_name'))
            ->groupBy('product_id');

        return DB::table('bidding_price')
            ->leftJoin('seller', 'bidding_price.seller_email', '=', 'seller.email')
            ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')
            ->leftJoinSub($productBrands, 'pb', function ($join) {
                $join->on('product.id', '=', 'pb.product_id');
            })
            // New tables: sub_categories & categories
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
            ->where('bidding_price.seller_email', $sellerEmail)
            // Only active categories/subcategories (status enum('1','2'); keep '1')
            ->where(function ($w) {
                $w->whereNull('c.status')->orWhere('c.status', '1');
            })
            ->where(function ($w) {
                $w->whereNull('sc.status')->orWhere('sc.status', '1');
            })
            ->select(
                // bidding_price
                'bidding_price.id as bidding_price_id',
                'bidding_price.rate as bidding_rate',
                'bidding_price.seller_email as bidding_price_seller_email',
                'bidding_price.product_id as bidding_price_product_id',
                'bidding_price.product_name as bidding_price_product_name',
                'bidding_price.payment_status as bidding_price_payment_status',

                // qutation_form
                'qutation_form.bid_time as bid_time',
                'qutation_form.material as material',
                'qutation_form.id as id',
                'qutation_form.name as name',
                'qutation_form.email as email',
                'qutation_form.product_id as qutation_form_product_id',
                'qutation_form.product_img as qutation_form_product_img',
                'pb.brand_name as qutation_form_product_brand',
                'qutation_form.product_name as qutation_form_product_name',
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
                'qutation_form.cat_id as qutation_form_cat_id',

                // seller
                'seller.id as seller_id',
                'seller.email as seller_email',
                'seller.name as seller_name',
                'seller.phone as seller_phone',
                'seller.hash_id as seller_hash_id',
                'seller.pro_ser as seller_pro_ser',

                // product
                'product.id as product_id',
                'product.title as product_name',
                'product.sub_id as product_sub_id',
                'product.user_id as product_user_id',
                'product.cat_id as product_cat_id',
                'product.super_id as product_super_id',
                'product.description as product_description',
                'product.image as product_image',
                'product.user_type as product_user_type',
                'product.insert_by as product_insert_by',
                'product.update_by as product_update_by',
                'product.slug as product_slug',
                'product.status as product_status',
                'product.order_by as product_order_by',

                // sub_categories (new schema)
                'sc.id as sub_id',
                'sc.name as sub_name',
                'sc.slug as sub_slug',
                'sc.category_id as sub_cat_id',
                'sc.created_at as sub_created_at',
                'sc.image as sub_image',
                'sc.status as sub_status',
                'sc.order_by as sub_order_by',

                // categories (new schema)
                'c.id as category_id',
                'c.name as category_name',
                'c.slug as category_slug',
                'c.created_at as category_created_at',
                'c.image as category_image',
                'c.status as category_status'
            );
    }

    /**
     * Append computed fields for display/use in Blade.
     */
    protected function appendComputedState(LengthAwarePaginator $records): LengthAwarePaginator
    {
        $collection = $records->getCollection();

        $collection = $collection->map(function ($record) {
            if (isset($record->bidding_price_payment_status)) {
                $record->bidding_price_payment_status = ucfirst((string) $record->bidding_price_payment_status);
            }

            $record->formatted_date = null;
            if (!empty($record->date_time)) {
                try {
                    $record->formatted_date = Carbon::parse($record->date_time)->format('Y-m-d');
                } catch (\Throwable $e) {
                    // leave null if parse fails
                }
            }

            return $record;
        });

        return $records->setCollection($collection);
    }
}
