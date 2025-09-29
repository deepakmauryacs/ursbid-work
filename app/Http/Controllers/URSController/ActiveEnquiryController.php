<?php

namespace App\Http\Controllers\URSController;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActiveEnquiryController extends Controller
{
    public function index(Request $request, $id = null)
    {
        $seller = $request->session()->get('seller');

        if (!$seller) {
            abort(403, 'Seller session not found.');
        }

        $perPage = (int) $request->input('r_page', 25);

        $filters = [
            'category' => $request->has('category') ? $request->input('category') : $id,
            'date' => $request->input('date'),
            'city' => $request->input('city'),
            'quantity' => $request->input('quantity'),
            'product_name' => $request->input('product_name'),
            'qutation_id' => $request->input('qutation_id'),
            'r_page' => $perPage,
        ];

        $query = $this->baseActiveEnquiryQuery($seller->id, $seller->email);

        if ($request->filled('category')) {
            $query->where('c.id', $request->input('category'));
        } elseif (!$request->has('category') && $id) {
            $query->where('c.id', $id);
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
            $query->where('product.title', 'like', '%' . $request->input('product_name') . '%');
        }

        if ($request->filled('qutation_id')) {
            $query->where('qutation_form.qutation_id', 'like', '%' . $request->input('qutation_id') . '%');
        }

        $blogs = $query
            ->orderBy('qutation_form.id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $blogs = $this->appendComputedState($blogs, $seller->email);

        $categoryData = DB::table('categories')
            ->select('id', 'name', DB::raw('name as title'))
            ->orderBy('name')
            ->get();

        if ($request->ajax()) {
            return view('ursdashboard.active-enquiry.partials.table', [
                'blogs' => $blogs,
                'data' => $filters,
                'sellerEmail' => $seller->email,
            ])->render();
        }

        return view('ursdashboard.active-enquiry.list', [
            'blogs' => $blogs,
            'data' => $filters,
            'category_data' => $categoryData,
            'sellerEmail' => $seller->email,
            'selectedCategoryId' => $id,
        ]);
    }

    public function show(Request $request, $id)
    {
        $seller = $request->session()->get('seller');

        if (!$seller) {
            abort(403, 'Seller session not found.');
        }

        $productBrands = DB::table('product_brands')
            ->select('product_id', DB::raw('MAX(brand_name) as brand_name'))
            ->groupBy('product_id');

        $query = DB::table('qutation_form')
            ->leftJoin('seller', 'qutation_form.email', '=', 'seller.email')
            ->leftJoin('product', 'qutation_form.product_id', '=', 'product.id')
            ->leftJoinSub($productBrands, 'pb', function ($join) {
                $join->on('product.id', '=', 'pb.product_id');
            })
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
                'pb.brand_name as qutation_form_product_brand',
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
                'qutation_form.unit as qutation_form_unit',
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
            )
            ->first();

        abort_if(!$query, 404);

        return view('ursdashboard.active-enquiry.view', [
            'query' => $query,
            'sellerEmail' => $seller->email,
        ]);
    }

    protected function baseActiveEnquiryQuery($sellerId, string $sellerEmail)
    {
        $currentDate = Carbon::now();

        $productBrands = DB::table('product_brands')
            ->select('product_id', DB::raw('MAX(brand_name) as brand_name'))
            ->groupBy('product_id');

        return DB::table('qutation_form')
            ->leftJoin('seller', 'qutation_form.email', '=', 'seller.email')
            ->leftJoin('product', 'qutation_form.product_id', '=', 'product.id')
            ->leftJoinSub($productBrands, 'pb', function ($join) {
                $join->on('product.id', '=', 'pb.product_id');
            })
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->where('qutation_form.email', '!=', $sellerEmail)
            ->whereRaw('FIND_IN_SET(?, qutation_form.seller_id)', [$sellerId])
            ->whereRaw('DATE_ADD(date_time, INTERVAL bid_time DAY) >= ?', [$currentDate])
            ->select(
                'qutation_form.id as id',
                'qutation_form.qutation_id as qutation_id',
                'qutation_form.name as name',
                'qutation_form.email as email',
                'qutation_form.product_id as qutation_form_product_id',
                'qutation_form.product_img as qutation_form_product_img',
                'qutation_form.product_name as qutation_form_product_name',
                'pb.brand_name as qutation_form_product_brand',
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
    }

    protected function appendComputedState(LengthAwarePaginator $blogs, string $sellerEmail): LengthAwarePaginator
    {
        $currentDate = Carbon::now();
        $collection = $blogs->getCollection();
        $ids = $collection->pluck('id')->filter()->values();

        $lockedByAdmin = collect();
        $lockedForSeller = collect();

        if ($ids->isNotEmpty()) {
            $lockedByAdmin = DB::table('bidding_price')
                ->whereIn('data_id', $ids)
                ->where('payment_status', 'success')
                ->where('action', '1')
                ->where('hide', '1')
                ->pluck('data_id')
                ->unique();

            $lockedForSeller = DB::table('bidding_price')
                ->whereIn('data_id', $ids)
                ->where('payment_status', 'success')
                ->where('seller_email', $sellerEmail)
                ->pluck('data_id')
                ->unique();
        }

        $collection->transform(function ($blog) use ($currentDate, $lockedByAdmin, $lockedForSeller) {
            $status = 'active';

            if (!empty($blog->date_time) && !empty($blog->bid_time)) {
                $expirationDate = Carbon::parse($blog->date_time)->addDays((int) $blog->bid_time);
                if ($currentDate->greaterThanOrEqualTo($expirationDate)) {
                    $status = 'deactive';
                }
            }

            $blog->status_badge = $status;
            $blog->show_bidding_button = $status === 'active'
                && !$lockedByAdmin->contains($blog->id)
                && !$lockedForSeller->contains($blog->id);

            return $blog;
        });

        return $blogs;
    }
}
