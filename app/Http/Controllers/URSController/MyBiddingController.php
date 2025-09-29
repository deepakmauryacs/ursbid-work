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

        $perPage = (int) $request->input('r_page', 25);

        $filters = [
            'keyword' => $request->input('keyword'),
            'category' => $request->input('category'),
            'date' => $request->input('date'),
            'city' => $request->input('city'),
            'quantity' => $request->input('quantity'),
            'product_name' => $request->input('product_name'),
            'qutation_id' => $request->input('qutation_id'),
            'r_page' => $perPage,
        ];

        $query = $this->baseMyBiddingQuery($seller->email);

        if ($request->filled('category')) {
            $category = $request->input('category');
            $query->where(function ($innerQuery) use ($category) {
                $innerQuery->where('category.id', $category)
                    ->orWhere('qutation_form.cat_id', 'like', '%' . $category . '%');
            });
        }

        if ($request->filled('date')) {
            $query->where('qutation_form.date_time', 'like', '%' . $request->input('date') . '%');
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
                $innerQuery->where('qutation_form.product_name', 'like', '%' . $productName . '%')
                    ->orWhere('product.title', 'like', '%' . $productName . '%');
            });
        }

        if ($request->filled('qutation_id')) {
            $query->where('qutation_form.qutation_id', 'like', '%' . $request->input('qutation_id') . '%');
        }

        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function ($innerQuery) use ($keyword) {
                $innerQuery->where('qutation_form.product_name', 'like', '%' . $keyword . '%')
                    ->orWhere('qutation_form.name', 'like', '%' . $keyword . '%')
                    ->orWhere('qutation_form.city', 'like', '%' . $keyword . '%');
            });
        }

        $records = $query
            ->orderByDesc('qutation_form.id')
            ->paginate($perPage)
            ->withQueryString();

        $records = $this->appendComputedState($records);

        $categoryData = DB::table('category')
            ->select('id', DB::raw('title as name'), DB::raw('title as title'))
            ->orderBy('title')
            ->get();

        if ($request->ajax()) {
            return view('ursdashboard.my-bidding.partials.table', [
                'records' => $records,
                'filters' => $filters,
            ])->render();
        }

        return view('ursdashboard.my-bidding.list', [
            'seller' => $seller,
            'filters' => $filters,
            'category_data' => $categoryData,
            'records' => $records,
            'datas' => $filters,
            'data' => $records,
            'total' => $records->total(),
        ]);
    }

    protected function baseMyBiddingQuery(string $sellerEmail)
    {
        return DB::table('bidding_price')
            ->leftJoin('seller', 'bidding_price.seller_email', '=', 'seller.email')
            ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')
            ->leftJoin('sub', 'product.sub_id', '=', 'sub.id')
            ->leftJoin('category', 'sub.cat_id', '=', 'category.id')
            ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
            ->where('bidding_price.seller_email', $sellerEmail)
            ->select(
                'bidding_price.id as bidding_price_id',
                'bidding_price.rate as bidding_rate',
                'bidding_price.seller_email as bidding_price_seller_email',
                'bidding_price.product_id as bidding_price_product_id',
                'bidding_price.product_name as bidding_price_product_name',
                'bidding_price.payment_status as bidding_price_payment_status',
                'qutation_form.bid_time as bid_time',
                'qutation_form.material as material',
                'qutation_form.id as id',
                'qutation_form.name as name',
                'qutation_form.email as email',
                'qutation_form.product_id as qutation_form_product_id',
                'qutation_form.product_img as qutation_form_product_img',
                'qutation_form.product_brand as qutation_form_product_brand',
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
                'seller.id as seller_id',
                'seller.email as seller_email',
                'seller.name as seller_name',
                'seller.phone as seller_phone',
                'seller.hash_id as seller_hash_id',
                'seller.pro_ser as seller_pro_ser',
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
                'sub.id as sub_id',
                'sub.title as sub_name',
                'sub.cat_id as sub_cat_id',
                'sub.post_date as sub_post_date',
                'sub.image as sub_image',
                'sub.slug as sub_slug',
                'sub.status as sub_status',
                'sub.order_by as sub_order_by',
                'category.id as category_id',
                'category.title as category_name',
                'category.post_date as category_post_date',
                'category.image as category_image',
                'category.slug as category_slug',
                'category.status as category_status'
            );
    }

    protected function appendComputedState(LengthAwarePaginator $records): LengthAwarePaginator
    {
        $collection = $records->getCollection();

        $collection = $collection->map(function ($record) {
            if (isset($record->bidding_price_payment_status)) {
                $record->bidding_price_payment_status = ucfirst($record->bidding_price_payment_status);
            }

            if (isset($record->date_time) && $record->date_time) {
                $record->formatted_date = Carbon::parse($record->date_time)->format('Y-m-d');
            } else {
                $record->formatted_date = null;
            }

            return $record;
        });

        return $records->setCollection($collection);
    }
}
