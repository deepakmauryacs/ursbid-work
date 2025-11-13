<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function active(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $currentDate = \Carbon\Carbon::now();
        
        $query = DB::table('qutation_form')
            ->leftJoin('seller', 'qutation_form.email', '=', 'seller.email')
            ->leftJoin('product', 'qutation_form.product_id', '=', 'product.id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->whereRaw("DATE_ADD(date_time, INTERVAL bid_time DAY) >= ?", [$currentDate])
            ->select(
                // qutation_form
                'qutation_form.id as id',
                'qutation_form.qutation_id as qutation_id',
                'qutation_form.name as name',
                'qutation_form.email as email',
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
                'qutation_form.date_time as date_time',
                'qutation_form.bid_time as bid_time',
                'qutation_form.material as qutation_form_material',
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

                // sub_categories
                'sc.id as sub_id',
                'sc.name as sub_name',
                'sc.category_id as sub_cat_id',
                'sc.image as sub_image',
                'sc.slug as sub_slug',
                'sc.status as sub_status',
                'sc.order_by as sub_order_by',

                // categories
                'c.id as category_id',
                'c.name as category_name',
                'c.image as category_image',
                'c.slug as category_slug',
                'c.status as category_status'
            );

        if ($request->filled('category')) {
            $query->where('c.id', $request->category);
        }
        if ($request->filled('date')) {
            $query->where('date_time', 'like', '%' . $request->date . '%');
        }
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }
        if ($request->filled('quantity')) {
            $query->where('quantity', 'like', '%' . $request->quantity . '%');
        }
        if ($request->filled('product_name')) {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }

        $quotations = $query->orderBy('date_time', 'DESC')->paginate($perPage)->appends($request->all());
        
        $categories = DB::table('categories')->where('status', 1)->orderBy('name')->get();
        
        if ($request->ajax()) {
            return view('ursbid-admin.quotations.partials.table', compact('quotations'))->render();
        }

        return view('ursbid-admin.quotations.active', compact('quotations', 'categories', 'perPage'));
    }
    
    public function closed(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $currentDate = \Carbon\Carbon::now();
        
        $query = DB::table('qutation_form')
            ->leftJoin('seller', 'qutation_form.email', '=', 'seller.email')
            ->leftJoin('product', 'qutation_form.product_id', '=', 'product.id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->whereRaw("DATE_ADD(date_time, INTERVAL bid_time DAY) <= ?", [$currentDate])
            ->select(
                // qutation_form
                'qutation_form.id as id',
                'qutation_form.qutation_id as qutation_id',
                'qutation_form.name as name',
                'qutation_form.email as email',
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
                'qutation_form.date_time as date_time',
                'qutation_form.bid_time as bid_time',
                'qutation_form.material as qutation_form_material',
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

                // sub_categories
                'sc.id as sub_id',
                'sc.name as sub_name',
                'sc.category_id as sub_cat_id',
                'sc.image as sub_image',
                'sc.slug as sub_slug',
                'sc.status as sub_status',
                'sc.order_by as sub_order_by',

                // categories
                'c.id as category_id',
                'c.name as category_name',
                'c.image as category_image',
                'c.slug as category_slug',
                'c.status as category_status'
            );

        if ($request->filled('category')) {
            $query->where('c.id', $request->category);
        }
        if ($request->filled('date')) {
            $query->where('date_time', 'like', '%' . $request->date . '%');
        }
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }
        if ($request->filled('quantity')) {
            $query->where('quantity', 'like', '%' . $request->quantity . '%');
        }
        if ($request->filled('product_name')) {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }

        $quotations = $query->orderBy('date_time', 'DESC')->paginate($perPage)->appends($request->all());
        $categories = DB::table('categories')->where('status', 1)->orderBy('name')->get();
        
        if ($request->ajax()) {
            return view('ursbid-admin.quotations.partials.table', compact('quotations'))->render();
        }

        return view('ursbid-admin.quotations.closed', compact('quotations', 'categories', 'perPage'));
    }
    
    public function view($id)
    {
        $quotation = DB::table('qutation_form')
            ->leftJoin('seller', 'qutation_form.email', '=', 'seller.email')
            ->leftJoin('product', 'qutation_form.product_id', '=', 'product.id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->where('qutation_form.id', $id)
            ->select(
                // qutation_form
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

                // sub_categories
                'sc.id as sub_id',
                'sc.name as sub_name',
                'sc.category_id as sub_cat_id',
                // 'sc.post_date as sub_post_date',
                'sc.image as sub_image',
                'sc.slug as sub_slug',
                'sc.status as sub_status',
                'sc.order_by as sub_order_by',

                // categories
                'c.id as category_id',
                'c.name as category_name',
                'c.image as category_image',
                'c.slug as category_slug',
                'c.status as category_status'
            )
            ->first();

        return view('ursbid-admin.quotations.view', compact('quotation'));
    }
    
    public function vewFile($id)
    {
        $quotation = DB::table('qutation_form')->where('id' , $id)->first();
        $images = explode(',', $quotation->image);
        return view('ursbid-admin.quotations.file', compact('quotation','images'));
    }
}
