<?php

namespace App\Http\Controllers\URSController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BiddingReceivedController extends Controller
{
    /**
     * Display the bidding received dashboard view with the existing buyer order logic.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $category = $request->input('category');
        $date = $request->input('date');
        $city = $request->input('city');
        $quantity = $request->input('quantity');
        $productName = $request->input('product_name');
        $quotationId = $request->input('qutation_id');

        $categoryData = DB::table('categories')
            ->select('id', 'name', DB::raw('name as title'))
            ->orderBy('name')
            ->get();
        $recordsPerPage = (int) $request->input('r_page', 25);

        $seller = $request->session()->get('seller');
        if (!$seller) {
            return redirect()->route('seller-login');
        }

        $buyerEmail = $seller->email;

        $productBrands = DB::table('product_brands')
            ->select('product_id', DB::raw('MAX(brand_name) as brand_name'))
            ->groupBy('product_id');

        $recordsQuery = DB::table('qutation_form')
            ->leftJoin('seller', 'qutation_form.email', '=', 'seller.email')
            ->leftJoin('product', 'qutation_form.product_id', '=', 'product.id')
            ->leftJoinSub($productBrands, 'pb', function ($join) {
                $join->on('product.id', '=', 'pb.product_id');
            })
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->where('qutation_form.email', $buyerEmail)
            ->select(
                'qutation_form.product_name as product_name',
                'qutation_form.bid_time as bid_time',
                'qutation_form.material as material',
                'qutation_form.id as id',
                'qutation_form.name as name',
                'qutation_form.email as email',
                'qutation_form.product_id as qutation_form_product_id',
                'qutation_form.product_img as qutation_form_product_img',
                'pb.brand_name as qutation_form_product_brand',
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
                'sc.id as sub_id',
                'sc.name as sub_name',
                'sc.category_id as sub_cat_id',
                'sc.created_at as sub_created_at',
                'sc.image as sub_image',
                'sc.slug as sub_slug',
                'sc.status as sub_status',
                'sc.order_by as sub_order_by',
                'c.id as category_id',
                'c.name as category_name',
                'c.created_at as category_created_at',
                'c.image as category_image',
                'c.slug as category_slug',
                'c.status as category_status'
            );

        if ($category) {
            $recordsQuery->where(function ($query) use ($category) {
                $query->where('c.id', $category)
                    ->orWhere('qutation_form.cat_id', 'like', '%' . $category . '%');
            });
        }

        if ($date) {
            $recordsQuery->where('qutation_form.date_time', 'like', '%' . $date . '%');
        }

        if ($city) {
            $recordsQuery->where('qutation_form.city', 'like', '%' . $city . '%');
        }

        if ($quantity) {
            $recordsQuery->where('qutation_form.quantity', 'like', '%' . $quantity . '%');
        }

        if ($productName) {
            $recordsQuery->where(function ($query) use ($productName) {
                $query->where('qutation_form.product_name', 'like', '%' . $productName . '%')
                    ->orWhere('product.title', 'like', '%' . $productName . '%');
            });
        }

        if ($quotationId) {
            $recordsQuery->where('qutation_form.id', 'like', '%' . $quotationId . '%');
        }

        if ($keyword) {
            $recordsQuery->where(function ($query) use ($keyword) {
                $query->where('qutation_form.product_name', 'like', '%' . $keyword . '%')
                    ->orWhere('qutation_form.name', 'like', '%' . $keyword . '%')
                    ->orWhere('qutation_form.city', 'like', '%' . $keyword . '%');
            });
        }

        $records = $recordsQuery->orderByDesc('qutation_form.id')->paginate($recordsPerPage);

        $filters = [
            'keyword' => $keyword,
            'date' => $date,
            'city' => $city,
            'quantity' => $quantity,
            'category' => $category,
            'product_name' => $productName,
            'qutation_id' => $quotationId,
            'r_page' => $recordsPerPage,
        ];

        return view('ursdashboard.bidding-received.list', [
            'seller' => $seller,
            'filters' => $filters,
            'category_data' => $categoryData,
            'records' => $records,
            'datas' => $filters,
            'data' => $records,
            'total' => $records->total(),
        ]);
    }
}
