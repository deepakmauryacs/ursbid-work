<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    public function accountingList(Request $request)
    {
        $keyword      = $request->input('keyword');
        $category     = $request->input('category');
        $date         = $request->input('date');
        $city         = $request->input('city');
        $quantity     = $request->input('quantity');
        $productName  = $request->input('product_name');

        $categoryData = DB::table('categories')
            ->select('categories.*', 'categories.name as title')
            ->get();

        $recordsPerPage = $request->input('r_page', 25);

        $query = DB::table('bidding_price')
            ->leftJoin('seller', 'bidding_price.seller_email', '=', 'seller.email')
            ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
            ->where('bidding_price.payment_status', 'success')
            ->orderByDesc('bidding_price.id')
            ->select(
                'bidding_price.id as bidding_price_id',
                'bidding_price.seller_email as seller_email',
                'bidding_price.price as price',
                'bidding_price.product_id as bidding_price_product_id',
                'bidding_price.product_name as bidding_price_product_name',
                'bidding_price.payment_status as bidding_price_payment_status',
                'bidding_price.filename as bidding_price_image',
                'bidding_price.rate as rate',
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
                'sc.id as sub_id',
                'sc.name as sub_name',
                'sc.category_id as sub_category_id',
                'sc.image as sub_image',
                'sc.slug as sub_slug',
                'sc.status as sub_status',
                'sc.order_by as sub_order_by',
                'c.id as category_id',
                'c.name as category_name',
                'c.image as category_image',
                'c.slug as category_slug',
                'c.status as category_status'
            );

        if ($category) {
            $query->where('c.id', $category);
        }

        if ($productName) {
            $query->where('product.title', 'like', '%' . $productName . '%');
        }

        if ($date) {
            $query->where('qutation_form.date_time', 'like', '%' . $date . '%');
        }

        if ($city) {
            $query->where('qutation_form.city', 'like', '%' . $city . '%');
        }

        if ($quantity) {
            $query->where('qutation_form.quantity', 'like', '%' . $quantity . '%');
        }

        $blogs = $query->paginate($recordsPerPage);

        $datas = [
            'keyword' => $keyword,
            'date' => $date,
            'city' => $city,
            'quantity' => $quantity,
            'category' => $category,
            'product_name' => $productName,
            'r_page' => $recordsPerPage,
        ];

        $total = $blogs->total();

        return view('ursbid-admin.accounting.accounting-list', compact('blogs', 'datas', 'categoryData', 'total'));
    }

    public function acceptedBiddingList(Request $request)
    {
        $keyword      = $request->input('keyword');
        $category     = $request->input('category');
        $date         = $request->input('date');
        $city         = $request->input('city');
        $quantity     = $request->input('quantity');
        $productName  = $request->input('product_name');

        $categoryData = DB::table('categories')->select('id', 'name')->orderBy('name')->get();

        $recordsPerPage = $request->input('r_page', 25);

        $data = DB::table('qutation_form')
            ->leftJoin('seller', 'qutation_form.email', '=', 'seller.email')
            ->leftJoin('product', 'qutation_form.product_id', '=', 'product.id')
            ->leftJoin('sub_categories', 'product.sub_id', '=', 'sub_categories.id')
            ->leftJoin('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->select(
                'qutation_form.product_name as product_name',
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
                'seller.id as seller_id',
                'seller.email as seller_email',
                'seller.name as seller_name',
                'seller.phone as seller_phone',
                'seller.hash_id as seller_hash_id',
                'seller.pro_ser as seller_pro_ser',
                'product.id as product_id',
                'product.title as product_name1',
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
                'sub_categories.id as sub_id',
                'sub_categories.name as sub_name',
                'sub_categories.category_id as sub_cat_id',
                'sub_categories.created_at as sub_created_at',
                'sub_categories.image as sub_image',
                'sub_categories.slug as sub_slug',
                'sub_categories.status as sub_status',
                'sub_categories.order_by as sub_order_by',
                'categories.id as category_id',
                'categories.name as category_name',
                'categories.created_at as category_created_at',
                'categories.image as category_image',
                'categories.slug as category_slug',
                'categories.status as category_status'
            );

        if ($category) {
            $data->where('categories.id', 'like', '%' . $category . '%');
        }

        if ($date) {
            $data->where('qutation_form.date_time', 'like', '%' . $date . '%');
        }

        if ($city) {
            $data->where('qutation_form.city', 'like', '%' . $city . '%');
        }

        if ($quantity) {
            $data->where('qutation_form.quantity', 'like', '%' . $quantity . '%');
        }

        if ($productName) {
            $data->where('qutation_form.product_name', 'like', '%' . $productName . '%');
        }

        $data = $data->orderBy('qutation_form.id', 'desc')->paginate($recordsPerPage);

        $total = $data->total();

        $datas = [
            'keyword' => $keyword,
            'date' => $date,
            'city' => $city,
            'quantity' => $quantity,
            'category' => $category,
            'product_name' => $productName,
            'r_page' => $recordsPerPage,
        ];

        return view('ursbid-admin.accounting.accepted-bidding-list', compact('datas', 'data', 'total', 'categoryData'));
    }

    public function priceList($dataId)
    {
        $data = DB::table('bidding_price')
            ->leftJoin('seller', 'bidding_price.seller_email', '=', 'seller.email')
            ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')
            ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
            ->leftJoin('sub_categories', 'product.sub_id', '=', 'sub_categories.id')
            ->leftJoin('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->where('bidding_price.data_id', $dataId)
            ->select(
                'bidding_price.id as bidding_price_id',
                'bidding_price.price as price',
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
                'sub_categories.id as sub_id',
                'sub_categories.name as sub_name',
                'sub_categories.category_id as sub_cat_id',
                'sub_categories.created_at as sub_created_at',
                'sub_categories.image as sub_image',
                'sub_categories.slug as sub_slug',
                'sub_categories.status as sub_status',
                'sub_categories.order_by as sub_order_by',
                'categories.id as category_id',
                'categories.name as category_name',
                'categories.created_at as category_created_at',
                'categories.image as category_image',
                'categories.slug as category_slug',
                'categories.status as category_status'
            )->get();

        $total = $data->count();

        return view('ursbid-admin.accounting.price-list', compact('data', 'total'));
    }

    public function acceptedList($dataId)
    {
        $data = DB::table('bidding_price')
            ->leftJoin('seller', 'bidding_price.seller_email', '=', 'seller.email')
            ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')
            ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
            ->leftJoin('sub_categories', 'product.sub_id', '=', 'sub_categories.id')
            ->leftJoin('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->where('bidding_price.data_id', $dataId)
            ->where('bidding_price.hide', '1')
            ->where('bidding_price.action', '1')
            ->select(
                'bidding_price.id as id',
                'bidding_price.price as price',
                'bidding_price.action as action',
                'bidding_price.data_id as data_id',
                'bidding_price.rate as rate',
                'bidding_price.seller_email as seller_email',
                'bidding_price.product_id as bidding_price_product_id',
                'bidding_price.product_name as bidding_price_product_name',
                'qutation_form.bid_time as bid_time',
                'qutation_form.material as material',
                'qutation_form.id as qf_id',
                'qutation_form.name as qf_name',
                'qutation_form.email as qf_email',
                'qutation_form.product_id as qf_product_id',
                'qutation_form.product_img as qf_product_img',
                'qutation_form.product_brand as qf_product_brand',
                'qutation_form.message as qf_message',
                'qutation_form.address as qf_address',
                'qutation_form.zipcode as qf_zipcode',
                'qutation_form.state as qf_state',
                'qutation_form.city as qf_city',
                'qutation_form.date_time as qf_date_time',
                'qutation_form.image as qf_image',
                'qutation_form.latitude as qf_latitude',
                'qutation_form.longitude as qf_longitude',
                'qutation_form.seller_id as qf_seller_id',
                'qutation_form.unit as qf_unit',
                'qutation_form.quantity as qf_quantity',
                'qutation_form.status as qf_status',
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
                'sub_categories.id as sub_id',
                'sub_categories.name as sub_name',
                'sub_categories.category_id as sub_cat_id',
                'sub_categories.created_at as sub_created_at',
                'sub_categories.image as sub_image',
                'sub_categories.slug as sub_slug',
                'sub_categories.status as sub_status',
                'sub_categories.order_by as sub_order_by',
                'categories.id as category_id',
                'categories.name as category_name',
                'categories.created_at as category_created_at',
                'categories.image as category_image',
                'categories.slug as category_slug',
                'categories.status as category_status'
            )->get();

        $total = $data->count();

        return view('ursbid-admin.accounting.accepted-list', compact('data', 'total'));
    }

    public function enquiryView($id)
    {
        $query = DB::table('qutation_form')
            ->leftJoin('seller', 'qutation_form.email', '=', 'seller.email')
            ->leftJoin('product', 'qutation_form.product_id', '=', 'product.id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->where('qutation_form.id', $id)
            ->select(
                'qutation_form.id as qutation_form_id',
                'qutation_form.name as qutation_form_name',
                'qutation_form.email as qutation_form_email',
                'qutation_form.product_id as qutation_form_product_id',
                'qutation_form.product_img as qutation_form_product_img',
                'qutation_form.product_name as qutation_form_product_name',
                'qutation_form.product_brand as qutation_form_product_brand',
                'qutation_form.message as qutation_form_message',
                'qutation_form.location as qutation_form_location',
                'qutation_form.address as qutation_form_address',
                'qutation_form.zipcode as qutation_form_zipcode',
                'qutation_form.state as qutation_form_state',
                'qutation_form.city as qutation_form_city',
                'qutation_form.bid_area as qutation_form_bid_area',
                'qutation_form.bid_time as qutation_form_bid_time',
                'qutation_form.material as qutation_form_material',
                'qutation_form.image as qutation_form_image',
                'qutation_form.latitude as qutation_form_latitude',
                'qutation_form.longitude as qutation_form_longitude',
                'qutation_form.seller_id as qutation_form_seller_id',
                'qutation_form.unit as qutation_form_date_unit',
                'qutation_form.quantity as qutation_form_quantity',
                'qutation_form.status as qutation_form_status',
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
                'sc.id as sub_id',
                'sc.name as sub_name',
                'sc.category_id as sub_cat_id',
                'sc.post_date as sub_post_date',
                'sc.image as sub_image',
                'sc.slug as sub_slug',
                'sc.status as sub_status',
                'sc.order_by as sub_order_by',
                'c.id as category_id',
                'c.name as category_name',
                'c.image as category_image',
                'c.slug as category_slug',
                'c.status as category_status'
            )
            ->first();

        return view('ursbid-admin.accounting.enquiry-view', compact('query'));
    }
}
