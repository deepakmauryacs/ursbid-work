<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\Advertise_mail;
use App\Mail\Contact_mail;
use App\Mail\Support_mail;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->where('status', '1')
            ->with(['subCategories:id,category_id,slug,name,image,order_by,status'])
            ->orderBy('name')
            ->get(['id', 'slug', 'name', 'image', 'status']);

        return view('frontend.home', compact('categories'));
    }

    public function biddlist(Request $request)
    {
        $keyword      = $request->input('keyword');
        $category     = $request->input('category');
        $date         = $request->input('date');
        $city         = $request->input('city');
        $quantity     = $request->input('quantity');
        $product_name = $request->input('product_name');

        $category_data  = DB::table('categories')
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
                // bidding_price
                'bidding_price.id as bidding_price_id',
                'bidding_price.seller_email as seller_email',
                'bidding_price.price as price',
                'bidding_price.product_id as bidding_price_product_id',
                'bidding_price.product_name as bidding_price_product_name',
                'bidding_price.payment_status as bidding_price_payment_status',
                'bidding_price.filename as bidding_price_image',
                'bidding_price.rate as rate',

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
                'sc.category_id as sub_category_id',
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

        if ($category) {
            $query->where('c.id', $category);
        }
        if ($product_name) {
            $query->where('product.title', 'like', '%' . $product_name . '%');
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
            'product_name' => $product_name,
            'r_page' => $recordsPerPage,
        ];

        $total = $query->count();

        return view('admin/enquiry/biddlist',  compact('blogs', 'datas', 'category_data'));
    }

    public function filter($cat_slug, $sub_slug)
    {
        $category = DB::table('categories')->where('slug', $cat_slug)->first();
        $subcat   = DB::table('sub_categories')->where('slug', $sub_slug)->first();

        if (!$category || !$subcat) {
            abort(404, 'Category or Subcategory not found');
        }

        $cid = $category->id;
        $sid = $subcat->id;
        $sub_category_description = $subcat->description;

        $subcategories = DB::table('sub_categories')
            ->where('status', '1')
            ->where('category_id', $cid)
            ->orderByRaw('COALESCE(`order_by`, 999999)')
            ->get();

        $products = DB::table('product')
            ->where('status', 1)
            ->where('cat_id', $cid)
            ->where('sub_id', $sid)
            ->orderBy('order_by')
            ->get();

        $productCount = $products->count();

        if ($productCount === 0) {
            $message = 'No data found';
            return view('frontend.filter', compact('subcategories', 'products', 'sub_slug', 'cat_slug', 'message', 'sid','sub_category_description'));
        }

        return view('frontend.filter', compact('subcategories', 'products', 'sub_slug', 'cat_slug', 'sid','sub_category_description'));
    }

    public function product($cat_slug, $sub_slug, $sup_slug)
    {
        $category = DB::table('categories')->where('slug', $cat_slug)->first();
        $subcat   = DB::table('sub_categories')->where('slug', $sub_slug)->first();
        $supcat   = DB::table('product')->where('slug', $sup_slug)->first();

        if (!$category || !$subcat || !$supcat) {
            abort(404, 'Category or Subcategory or Supercategory not found');
        }

        $cid   = $category->id;
        $sid   = $subcat->id;
        $supid = $supcat->id;

        $supcategories = DB::table('product')
            ->where('status', 1)
            ->where('cat_id', $cid)
            ->get();

        $units = DB::table('unit')
            ->where('sub_category_id', $supcat->sub_id)
            ->where('status', 1)
            ->get();

        $products = DB::table('product_brands')
            ->where('status', 1)
            ->where('category_id',$cid)
            ->where('sub_category_id',$sid)
            ->where('product_id', $supid)
            ->get();

        $productCount = $products->count();

        if ($productCount === 0) {
            $products = $supcat;
            $message = 'No data found';
            return view('frontend.product-detail', compact('supcategories', 'category', 'products', 'sid', 'sub_slug', 'units', 'cat_slug', 'message', 'sup_slug'));
        }

        return view('frontend.product', compact('supcategories', 'units', 'products', 'sid', 'sub_slug', 'cat_slug', 'sup_slug'));
    }

    public function productdetailsearch($slug)
    {
        $products = DB::table('product')
            ->where('status', 1)
            ->first();

        $super       = $products->super_id;
        $category_id = $products->cat_id;

        $superproducts = DB::table('super')
            ->where('id', $super)
            ->where('status', 1)
            ->first();

        $category = DB::table('categories')
            ->where('id', $category_id)
            ->where('status', 1)
            ->first();

        $sid = $products->sub_id;

        $units = DB::table('unit')
            ->where('sub_category_id', $sid)
            ->where('status', 1)
            ->get();

        return view('frontend/product-detail', compact('products', 'units', 'category', 'superproducts', 'sid'));
    }

    public function product_detail($slug)
    {
        $superproducts = DB::table('product_brands')
            ->where('slug', $slug)
            ->where('status', 1)
            ->first();

        $products = DB::table('product')
            ->where('id', $superproducts->product_id)
            ->where('status', 1)
            ->first();

        $category = DB::table('categories')
            ->where('id', $superproducts->category_id)
            ->where('status', 1)
            ->first();

        $sid = $superproducts->sub_category_id;

        $units = DB::table('unit')
            ->where('sub_category_id', $sid)
            ->where('status', 1)
            ->get();

        return view('frontend/product-detail', compact('products', 'sid', 'units', 'category', 'superproducts'));
    }

    public function qutation_form(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipcode' => 'required',
            'material' => 'required',
            'bid_area' => 'required',
            'bid_time' => 'required',
            'quantity' => 'required',
            'g-recaptcha-response' => 'required',
            'term_and_condition' => 'required',
            'images' => 'sometimes|array',
            'images.*' => 'mimes:jpeg,png,jpg,pdf,doc,docx|max:20048',
        ]);

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip()
        ]);

        $responseBody = $response->json();

        if (!$responseBody['success']) {
            return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed'])->withInput();
        }

        $latitude          = $request->latitude;
        $subcategory_get   = $request->subcategory_check;

        $subcategory_get_ids = DB::table('sub_categories')
            ->where('id', $subcategory_get)
            ->where('status', '1')
            ->first();

        $longitude = $request->longitude;
        $bidArea   = $request->bid_area;
        $subcategory_get_name = $subcategory_get_ids->name ?? null;

        if ($bidArea == 5000) {
            $sellers = DB::table('seller')
                ->whereRaw("FIND_IN_SET(?, pro_ser)", [$subcategory_get_name])
                ->get();
        } else {
            $sellers = DB::table('seller')
                ->select(DB::raw('*'))
                ->selectRaw("( 6371 * acos( cos( radians(?) ) *
                        cos( radians( latitude ) )
                        * cos( radians( longitude ) - radians(?)
                        ) + sin( radians(?) ) *
                        sin( radians( latitude ) ) )
                      ) AS distance", [$latitude, $longitude, $latitude])
                ->whereRaw("FIND_IN_SET(?, pro_ser)", [$subcategory_get_name])
                ->havingRaw("distance < ?", [$bidArea])
                ->get();
        }

        $sellerIds = $sellers->pluck('id')->implode(',');

        $data = [
            'qutation_id'  => $this->generateUniqueQuotationId(),
            'latitude'      => $latitude,
            'longitude'     => $longitude,
            'name'          => $request->name,
            'email'         => $request->email,
            'city'          => $request->city,
            'address'       => $request->address,
            'state'         => $request->state,
            'zipcode'       => $request->zipcode,
            'unit'          => $request->unit,
            'message'       => $request->message,
            'bid_area'      => $bidArea,
            'bid_time'      => $request->bid_time,
            'material'      => $request->material,
            'product_brand' => $request->product_brand,
            'product_name'  => $request->product_name,
            'quantity'      => $request->quantity,
            'product_id'    => $request->product_id,
            'product_img'   => $request->product_img,
            'seller_id'     => $sellerIds,
            'cat_id'        => $request->cat_id,
        ];

        if ($request->hasFile('images')) {
            $files = $request->file('images');
            $filenames = [];
            foreach ($files as $file) {
                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('bidfile'), $filename);
                $filenames[] = $filename;
            }
            $data['image'] = implode(',', $filenames);
        }

        $inserted = DB::table('qutation_form')->insert($data);

        if ($inserted) {
            return back()->with('success', 'Quotation Form submitted successfully.');
        } else {
            return back()->withErrors(['error' => 'Insertion Failed.'])->withInput();
        }
    }

    private function generateUniqueQuotationId(): string
    {
        $prefix = 'URSBID-';
        $length = 10;
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        do {
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[random_int(0, strlen($characters) - 1)];
            }

            $quotationId = $prefix . $randomString;
        } while (DB::table('qutation_form')->where('qutation_id', $quotationId)->exists());

        return $quotationId;
    }

    public function rating(Request $request)
    {
        $name   = $request->session()->get('seller')->name;
        $star   = $request->star;
        $review = $request->review;
        $email  = $request->email;

        $data = [
            'name'   => $name,
            'email'  => $email,
            'star'   => $star,
            'review' => $review
        ];

        $inserted = DB::table('rating')->insert($data);

        if ($inserted) {
            return back()->with('success', ' submitted successfully.');
        } else {
            return back()->withErrors(['error' => 'Insertion Failed.'])->withInput();
        }
    }

    public function fetchOptions(Request $request)
    {
        if ($request->has('categories')) {
            $categories = $request->input('categories');

            $subcategories = DB::table('sub_categories')
                ->whereIn('category_id', $categories)
                ->where('status', '1')
                ->get();

            $options = '';
            foreach ($subcategories as $sub) {
                $options .= '<label><input type="checkbox" name="pro_ser[]" value="' . $sub->name . '"> ' . $sub->name . '</label><br>';
            }

            return response()->json($options);
        }

        return response()->json('<p>No subcategories available</p>');
    }

    public function fetchOptionsback(Request $request)
    {
        if ($request->has('categories')) {
            $categories = $request->input('categories');

            $pro_ser = session()->get('seller')->pro_ser ?? '';
            $selectedServices = array_filter(array_map('trim', explode(',', $pro_ser)));

            $subcategories = DB::table('sub_categories')
                ->whereIn('category_id', $categories)
                ->where('status', '1')
                ->get();

            $options = '';
            foreach ($subcategories as $sub) {
                $checked = in_array($sub->name, $selectedServices) ? 'checked' : '';
                $options .= '<label><input type="checkbox" name="pro_ser[]" value="' . $sub->name . '" ' . $checked . '> ' . $sub->name . '</label><br>';
            }

            return response()->json($options);
        }

        return response()->json('<p>No subcategories available</p>');
    }

    public function forgot_password()
    {
        return view('frontend.forgot-password');
    }

    public function c_update(Request $request)
    {
        return view('frontend.update-pass');
    }

    public function cforgot_password(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $existingRecord = DB::table('seller')
            ->where('email', $request->email)
            ->where('verify', 1)
            ->first();

        if (!$existingRecord) {
            return back()->withErrors(['general' => 'Sorry, invalid email'])->withInput();
        }

        $candidateId = $existingRecord->hash_id;

        return view('frontend.update-pass', compact('candidateId'));
    }

    public function cfupdate_password(Request $request, $hash_id)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $hashedPassword = bcrypt($request->password);

        $updated = DB::table('seller')->where('hash_id', $hash_id)->update(['password' => $hashedPassword]);

        if ($updated) {
            return back()->with('success', 'Password has been updated successfully.');
        } else {
            return redirect()->back()->withErrors(['error' => 'Update failed.'])->withInput();
        }
    }

    public function search(Request $request)
    {
        $keyword  = $request->search;
        $category = $request->category;

        $products = DB::table('product')
            ->leftJoin('categories as c', 'product.cat_id', '=', 'c.id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('super', 'product.super_id', '=', 'super.id')
            ->select(
                'product.*',
                'product.image as img',
                'product.id as product_id',
                'product.title as product_title',
                'product.image as product_image',
                'product.slug as product_slug',
                'product.status as product_status',
                'c.id as category_id',
                'c.name as category_name',
                'sc.id as sub_id',
                'sc.name as sub_name',
                'super.title as super_title'
            );

        if ($keyword) {
            $products->where('product.title', 'like', '%' . $keyword . '%');
        }
        if ($category) {
            $products->where('c.id', $category);
        }

        $products = $products->get();
        $productCount = $products->count();

        $data = [
            'keyword' => $keyword,
            'category' => $category,
        ];

        if ($productCount === 0) {
            $message = 'No data found';
            return view('frontend.search', compact('message', 'data'));
        }

        return view('frontend.search', compact('products', 'data'));
    }

    public function quotationlist(Request $request)
    {
        $keyword      = $request->input('keyword');
        $category     = $request->input('category');
        $date         = $request->input('date');
        $city         = $request->input('city');
        $quantity     = $request->input('quantity');
        $product_name = $request->input('product_name');
        $recordsPerPage = $request->input('r_page', 15);
        $currentDate = \Carbon\Carbon::now();
        $category_data = DB::table('categories')
            ->select('categories.*', 'categories.name as title')
            ->get();

        $query = DB::table('qutation_form')
            ->leftJoin('seller', 'qutation_form.email', '=', 'seller.email')
            ->leftJoin('product', 'qutation_form.product_id', '=', 'product.id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->whereRaw("DATE_ADD(date_time, INTERVAL bid_time DAY) >= ?", [$currentDate])
            ->select(
                // qutation_form
                'qutation_form.id as id',
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

        if ($category) {
            $query->where('c.id', $category);
        }
        if ($date) {
            $query->where('date_time', 'like', '%' . $date . '%');
        }
        if ($city) {
            $query->where('city', 'like', '%' . $city . '%');
        }
        if ($quantity) {
            $query->where('quantity', 'like', '%' . $quantity . '%');
        }
        if ($product_name) {
            $query->where('product_name', 'like', '%' . $product_name . '%');
        }

        $blogs = $query->orderBy('id', 'desc')->paginate($recordsPerPage);

        $data = [
            'keyword' => $keyword,
            'date' => $date,
            'city' => $city,
            'quantity' => $quantity,
            'category' => $category,
            'product_name' => $product_name,
            'r_page' => $recordsPerPage,
        ];

        return view('admin.enquiry.list', compact('blogs', 'data', 'category_data'));
    }

    public function deactivelist(Request $request)
    {
        $keyword      = $request->input('keyword');
        $category     = $request->input('category');
        $date         = $request->input('date');
        $city         = $request->input('city');
        $quantity     = $request->input('quantity');
        $product_name = $request->input('product_name');
        $recordsPerPage = $request->input('r_page', 15);
        $currentDate = \Carbon\Carbon::now();
        $category_data = DB::table('categories')
            ->select('categories.*', 'categories.name as title')
            ->get();

        $query = DB::table('qutation_form')
            ->leftJoin('seller', 'qutation_form.email', '=', 'seller.email')
            ->leftJoin('product', 'qutation_form.product_id', '=', 'product.id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->whereRaw("DATE_ADD(date_time, INTERVAL bid_time DAY) <= ?", [$currentDate])
            ->select(
                // qutation_form
                'qutation_form.id as id',
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

        if ($category) {
            $query->where('c.id', $category);
        }
        if ($date) {
            $query->where('date_time', 'like', '%' . $date . '%');
        }
        if ($city) {
            $query->where('city', 'like', '%' . $city . '%');
        }
        if ($quantity) {
            $query->where('quantity', 'like', '%' . $quantity . '%');
        }
        if ($product_name) {
            $query->where('product_name', 'like', '%' . $product_name . '%');
        }

        $blogs = $query->orderBy('id', 'desc')->paginate($recordsPerPage);

        $data = [
            'keyword' => $keyword,
            'date' => $date,
            'city' => $city,
            'quantity' => $quantity,
            'category' => $category,
            'product_name' => $product_name,
            'r_page' => $recordsPerPage,
        ];

        return view('admin.enquiry.deactivelist', compact('blogs', 'data', 'category_data'));
    }

    public function vewsell($id)
    {
        $query = DB::table('qutation_form')
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
                'sc.created_at as sub_post_date',
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

        return view('/admin/enquiry/view', compact('query'));
    }

    public function vewfile($id){
        $query = DB::table('qutation_form')->where('id' , $id)->first();
        $images = explode(',', $query->image);
        return view('/admin/enquiry/file', compact('query','images'));
    }

    public function autofillAddress(Request $request)
    {
        $ip = $request->query('ip');

        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $response = Http::get("https://ipinfo.io/{$ip}?token=8d6fa5c7bf454c");

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['error' => 'Unable to fetch address details'], 500);
            }
        } else {
            return response()->json(['error' => 'Invalid IP address'], 400);
        }
    }

    public function contact_inc(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'service' => 'required',
                'g-recaptcha-response' => 'required',
                'message' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip()
            ]);

            $responseBody = $response->json();

            if (!$responseBody['success']) {
                return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed'])->withInput();
            }

            $name    = $request->input('name');
            $email   = $request->input('email');
            $phone   = $request->input('phone');
            $message = $request->input('message');
            $service = $request->input('service');

            $data = [
                'Name' => $name,
                'Email' => $email,
                'Phone' => $phone,
                'Service' => $service,
                'Message' => $message,
            ];

            Mail::to($email)
                ->bcc("support@ursbid.com")
                ->send(new Contact_mail($data));

            return redirect()->back()->withSuccess('Thank you for contacting us!');

        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            Session::flash('error', 'An error occurred while processing. Please try again later. Error: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function advertise_inc(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'service' => 'required',
                'message' => 'required',
                'g-recaptcha-response' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip()
            ]);

            $responseBody = $response->json();

            if (!$responseBody['success']) {
                return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed'])->withInput();
            }

            $name    = $request->input('name');
            $email   = $request->input('email');
            $phone   = $request->input('phone');
            $message = $request->input('message');
            $service = $request->input('service');

            $data = [
                'Name' => $name,
                'Email' => $email,
                'Phone' => $phone,
                'Service' => $service,
                'Message' => $message,
            ];

            Mail::to($email)
                ->bcc("advertise@ursbid.com")
                ->send(new Advertise_mail($data));

            return redirect()->back()->withSuccess('Thank you for contacting us!');

        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            Session::flash('error', 'An error occurred while processing. Please try again later. Error: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function acceptedbidding(Request $request){
        $keyword      = $request->input('keyword');
        $category     = $request->input('category');
        $date         = $request->input('date');
        $city         = $request->input('city');
        $quantity     = $request->input('quantity');
        $product_name = $request->input('product_name');

        $category_data  = DB::table('categories')
            ->select('categories.*', 'categories.name as title')
            ->get();
        $recordsPerPage = $request->input('r_page', 25);

        $data = DB::table('bidding_price')
            ->leftJoin('seller', 'bidding_price.seller_email', '=', 'seller.email')
            ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
            ->where('bidding_price.payment_status','success')
            ->where('bidding_price.action','1')
            ->where('bidding_price.hide','1')
            ->orderBy('bidding_price.id', 'desc')
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

        if ($category){
            $data->where('c.id', $category);
        }
        if ($product_name){
            $data->where('product.title', 'like', '%' . $product_name . '%');
        }
        if ($date) {
            $data->where('qutation_form.date_time', 'like', '%' . $date . '%');
        }
        if ($city){
            $data->where('qutation_form.city', 'like', '%' . $city . '%');
        }
        if ($quantity){
            $data->where('qutation_form.quantity', 'like', '%' . $quantity . '%');
        }

        $data = $data->paginate($recordsPerPage);

        $datas = [
            'keyword' => $keyword,
            'date' => $date,
            'city' => $city,
            'quantity' => $quantity,
            'category' => $category,
            'product_name' => $product_name,
            'r_page' => $recordsPerPage,
        ];

        $total = $data->count();

        return view('admin/enquiry/acceptedbidding',  compact('data','datas','total','category_data'));
    }

    public function totalbidding(Request $request){
        $keyword      = $request->input('keyword');
        $category     = $request->input('category');
        $date         = $request->input('date');
        $city         = $request->input('city');
        $quantity     = $request->input('quantity');
        $product_name = $request->input('product_name');

        $category_data  = DB::table('categories')
            ->select('categories.*', 'categories.name as title')
            ->get();
        $recordsPerPage = $request->input('r_page', 25);

        $data = DB::table('bidding_price')
            ->leftJoin('seller', 'bidding_price.seller_email', '=', 'seller.email')
            ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
            ->where('bidding_price.payment_status','success')
            ->orderBy('bidding_price.id', 'desc')
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

        if ($category){
            $data->where('c.id', $category);
        }
        if ($product_name){
            $data->where('product.title', 'like', '%' . $product_name . '%');
        }
        if ($date) {
            $data->where('qutation_form.date_time', 'like', '%' . $date . '%');
        }
        if ($city){
            $data->where('qutation_form.city', 'like', '%' . $city . '%');
        }
        if ($quantity){
            $data->where('qutation_form.quantity', 'like', '%' . $quantity . '%');
        }

        $data = $data->paginate($recordsPerPage);

        $datas = [
            'keyword' => $keyword,
            'date' => $date,
            'city' => $city,
            'quantity' => $quantity,
            'category' => $category,
            'product_name' => $product_name,
            'r_page' => $recordsPerPage,
        ];

        $total = $data->count();

        return view('admin/enquiry/totalbidding',  compact('data','datas','total','category_data'));
    }

    public function support_inc(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'message' => 'required',
                'g-recaptcha-response' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip()
            ]);

            $responseBody = $response->json();

            if (!$responseBody['success']) {
                return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed'])->withInput();
            }

            $name    = $request->input('name');
            $email   = $request->input('email');
            $phone   = $request->input('phone');
            $message = $request->input('message');

            $data = [
                'Name' => $name,
                'Email' => $email,
                'Phone' => $phone,
                'Message' => $message,
            ];

            Mail::to($email)
                ->bcc("support@ursbid.com")
                ->send(new Support_mail($data));

            return redirect()->back()->withSuccess('Thank you for contacting us!');

        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            Session::flash('error', 'An error occurred while processing. Please try again later. Error: ' . $e->getMessage());
            return redirect()->back();
        }
    }
    
    public function blogDetail($slug)
    {
        $blog = DB::table('blogs')->where('slug', $slug)->where('status', 1)->first();
        if (!$blog) {
            abort(404);
        }

        $featurePost = DB::table('blogs')
            ->where('status', 1)
            ->where('id', '!=', $blog->id)
            ->orderByDesc('id')
            ->first();

        $popularPosts = DB::table('blogs')
            ->where('status', 1)
            ->where('id', '!=', $blog->id)
            ->orderByDesc('id')
            ->skip(1)
            ->take(3)
            ->get();

        $relatedPosts = DB::table('blogs')
            ->where('status', 1)
            ->where('id', '!=', $blog->id)
            ->orderByDesc('id')
            ->take(4)
            ->get();

        $categories = DB::table('categories')
            ->leftJoin('sub_categories', 'sub_categories.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('COUNT(sub_categories.id) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->get();

        $comments = DB::table('blog_comments')
            ->where('blog_id', $blog->id)
            ->orderByDesc('id')
            ->get();

        $formattedDate = date('d-m-Y');

        return view('frontend.blog-detail', compact('blog', 'featurePost', 'popularPosts', 'relatedPosts', 'categories', 'comments', 'formattedDate'));
    }

    public function blogComment(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $blog = DB::table('blogs')->where('slug', $slug)->where('status', 1)->first();
        if (!$blog) {
            return response()->json(['status' => false, 'message' => 'Blog not found'], 404);
        }

        DB::table('blog_comments')->insert([
            'blog_id' => $blog->id,
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
            'created_at' => now(),
        ]);

        return response()->json(['status' => true, 'message' => 'Comment submitted successfully']);
    }
}
