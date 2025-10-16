<?php

namespace App\Http\Controllers\URSController;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcceptedBiddingController extends Controller
{
    public function index(Request $request)
    {
        $seller = $request->session()->get('seller');

        if (!$seller) {
            abort(403, 'Seller session not found.');
        }

        $perPage = (int) $request->input('r_page', 25);

        $filters = [
            'qutation_id'  => $request->input('qutation_id'),
            'category'     => $request->input('category'),
            'date'         => $request->input('date'),
            'city'         => $request->input('city'),
            'quantity'     => $request->input('quantity'),
            'product_name' => $request->input('product_name'),
            'r_page'       => $perPage,
        ];

        $query = $this->baseAcceptedBidsQuery($seller->email);

        // filter by NEW categories.id
        if ($request->filled('category')) {
            $query->where('categories.id', $request->input('category'));
        }

        if ($request->filled('date')) {
            $query->whereDate('qutation_form.date_time', $request->input('date'));
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
                $innerQuery->where('product.title', 'like', '%' . $productName . '%')
                    ->orWhere('qutation_form.product_name', 'like', '%' . $productName . '%');
            });
        }

        if ($request->filled('qutation_id')) {
            $query->where('qutation_form.qutation_id', 'like', '%' . $request->input('qutation_id') . '%');
        }

        $records = $query
            ->orderByDesc('qutation_form.id')
            ->paginate($perPage)
            ->withQueryString();

        $records = $this->appendComputedTotals($records);

        // NEW categories list
        $categoryData = DB::table('categories')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        if ($request->ajax()) {
            return view('ursdashboard.accepted-bidding.partials.table', [
                'records' => $records,
                'filters' => $filters,
            ])->render();
        }

        return view('ursdashboard.accepted-bidding.list', [
            'seller'        => $seller,
            'filters'       => $filters,
            'category_data' => $categoryData,
            'records'       => $records,
            'datas'         => $filters,
            'total'         => $records->total(),
        ]);
    }

    /**
     * Base query adjusted to join new `sub_categories` and `categories`.
     *
     * Note:
     * - We keep using product.sub_id (as per your current schema) and join it to sub_categories.id.
     * - Category is derived via sub_categories.category_id → categories.id.
     */
    protected function baseAcceptedBidsQuery(string $sellerEmail)
    {
        return DB::table('bidding_price')
            ->leftJoin('seller', 'bidding_price.seller_email', '=', 'seller.email')
            ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')

            // NEW tables
            ->leftJoin('sub_categories', 'product.sub_id', '=', 'sub_categories.id')
            ->leftJoin('categories', 'sub_categories.category_id', '=', 'categories.id')

            ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
            ->where('bidding_price.seller_email', $sellerEmail)
            ->where('bidding_price.payment_status', 'success')
            ->where('bidding_price.action', '1')
            ->where('bidding_price.hide', '1')
            ->select(
                // bidding_price
                'bidding_price.id as bidding_price_id',
                'bidding_price.rate as rate',
                'bidding_price.price as price',
                'bidding_price.seller_email as seller_email',
                'bidding_price.product_id as bidding_price_product_id',
                'bidding_price.product_name as bidding_price_product_name',
                'bidding_price.payment_status as bidding_price_payment_status',
                'bidding_price.filename as bidding_price_filename',

                // qutation_form
                'qutation_form.bid_time as bid_time',
                'qutation_form.material as material',
                'qutation_form.id as id',
                'qutation_form.name as name',
                'qutation_form.email as email',
                'qutation_form.product_id as qutation_form_product_id',
                'qutation_form.product_img as qutation_form_product_img',
                'qutation_form.product_brand as qutation_form_product_brand',
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
                'product.cat_id as product_cat_id',       // kept in case you still store cat_id on product
                'product.super_id as product_super_id',
                'product.description as product_description',
                'product.image as product_image',
                'product.user_type as product_user_type',
                'product.insert_by as product_insert_by',
                'product.update_by as product_update_by',
                'product.slug as product_slug',
                'product.status as product_status',
                'product.order_by as product_order_by',

                // sub_categories
                'sub_categories.id as sub_id',
                'sub_categories.name as sub_name',
                'sub_categories.category_id as sub_cat_id',
                'sub_categories.created_at as sub_created_at',
                'sub_categories.image as sub_image',
                'sub_categories.slug as sub_slug',
                'sub_categories.status as sub_status',
                'sub_categories.order_by as sub_order_by',

                // categories
                'categories.id as category_id',
                'categories.name as category_name',
                'categories.created_at as category_created_at',
                'categories.image as category_image',
                'categories.slug as category_slug',
                'categories.status as category_status'
            );
    }

    protected function appendComputedTotals(LengthAwarePaginator $records): LengthAwarePaginator
    {
        $records->getCollection()->transform(function ($record) {
            $quantity = $record->quantity;
            $numericQuantity = 0.0;

            if (is_numeric($quantity)) {
                $numericQuantity = (float) $quantity;
            } elseif (is_string($quantity) && preg_match('/\d+(\.\d+)?/', $quantity, $matches)) {
                $numericQuantity = (float) ($matches[0] ?? 0);
            }

            $record->calculated_total_price = round($numericQuantity * (float) ($record->rate ?? 0), 2);

            return $record;
        });

        return $records;
    }
}
