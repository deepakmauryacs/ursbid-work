<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
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
   


    public function biddlist(Request $request){
        $keyword = $request->input('keyword');
        $category = $request->input('category');
        $date = $request->input('date');
        $city = $request->input('city');
        $quantity = $request->input('quantity');
        $product_name = $request->input('product_name');

        $category_data=DB::table('category')->get();
        $recordsPerPage = $request->input('r_page', 25);
    

        $query = DB::table('bidding_price')
        
        ->leftJoin('seller', 'bidding_price.seller_email', '=', 'seller.email')
        ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')
        ->leftJoin('sub', 'product.sub_id', '=', 'sub.id')
        ->leftJoin('category', 'sub.cat_id', '=', 'category.id')
        ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
        // ->where('bidding_price.seller_email',$buyer_email)
        ->where('bidding_price.payment_status','success')
        ->orderByDesc('bidding_price.id')
        ->select(
            // bidding_price columns
            'bidding_price.id as bidding_price_id',
            'bidding_price.seller_email as seller_email',
            'bidding_price.price as price',
            'bidding_price.product_id as bidding_price_product_id',
            'bidding_price.product_name as bidding_price_product_name',
            'bidding_price.payment_status as bidding_price_payment_status',
            'bidding_price.filename as bidding_price_image',
            
            'bidding_price.rate as rate',


            // qf
                    'qutation_form.bid_time as bid_time',
                    'qutation_form.material as material',
                    'qutation_form.id as id',
                    'qutation_form.name as name',
                    'qutation_form.email as email',
                    'qutation_form.product_id as qutation_form_product_id',
                    'qutation_form.product_img as qutation_form_product_img',
                    // 'qutation_form.product_name	as qutation_form_product_name',
                    'qutation_form.product_brand as qutation_form_product_brand',
                    'qutation_form.message as qutation_form_message',
                    // 'qutation_form.location	as qutation_form_location',
                    'qutation_form.address as qutation_form_address',
                    'qutation_form.zipcode as qutation_form_zipcode',
                    'qutation_form.state as qutation_form_state',
                    'qutation_form.city as qutation_form_city',
                    'qutation_form.date_time as date_time',
                    // 'qutation_form.bid_time	as bid_time',
                    'qutation_form.image as qutation_form_image',
                    'qutation_form.latitude as qutation_form_latitude',
                    'qutation_form.longitude as qutation_form_longitude',
                    'qutation_form.seller_id as qutation_form_seller_id',
                    'qutation_form.unit	 as unit',
                    'qutation_form.quantity as quantity',
                    'qutation_form.status as qutation_form_status',

            // seller columns
            'seller.id as seller_id',
            'seller.email as seller_email',
            'seller.name as seller_name',
            'seller.phone as seller_phone',
            'seller.hash_id as seller_hash_id',
            'seller.pro_ser as seller_pro_ser',

            // product columns
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

            // sub columns
            'sub.id as sub_id',
            'sub.title as sub_name',
            'sub.cat_id as sub_cat_id',
            'sub.post_date as sub_post_date',
            'sub.image as sub_image',
            'sub.slug as sub_slug',
            'sub.status as sub_status',
            'sub.order_by as sub_order_by',

            // category columns
            'category.id as category_id',
            'category.title as category_name',
            'category.post_date as category_post_date',
            'category.image as category_image',
            'category.slug as category_slug',
            'category.status as category_status'

        );
            
        if($category){
            $query->where('category.id', 'like', '%' . $category . '%');
        }
        if($product_name){
            $query->where('product.title', 'like', '%' . $product_name . '%');
        }
        
    
       if ($date) {
          $query->where('qutation_form.date_time', 'like', '%' . $date . '%');
       }

       if($city){
          $query->where('qutation_form.city', 'like', '%' . $city . '%');
       }
       if($quantity){
          $query->where('qutation_form.quantity', 'like', '%' . $quantity . '%');
       }
       
   
    //    $blogs = $query->orderBy('qutation_form.id', 'desc')->paginate($recordsPerPage);
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

    
        
         


        $total=$query->count();

        return view('admin/enquiry/biddlist',  compact('blogs', 'datas','category_data'));
    }





    public function filter($cat_slug, $sub_slug)
    {
        $category = DB::table('category')
            ->where('slug', $cat_slug)
            ->first();

        $subcat = DB::table('sub')
            ->where('slug', $sub_slug)
            ->first();
    
        if (!$category || !$subcat) {
            abort(404, 'Category or Subcategory not found');
        }
    
        $cid = $category->id;
        $sid = $subcat->id;
    
        $subcategories = DB::table('sub')
            ->where('status', 1)
            ->where('cat_id', $cid)
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
                return view('frontend.filter', compact('subcategories', 'products', 'sub_slug', 'cat_slug' , 'message','sid'));
            }
        
        return view('frontend.filter', compact('subcategories', 'products', 'sub_slug', 'cat_slug','sid'));
    }

    
    public function product($cat_slug, $sub_slug, $sup_slug)
    {

        $category = DB::table('category')
            ->where('slug', $cat_slug)
            ->first();

        $subcat = DB::table('sub')
            ->where('slug', $sub_slug)
            ->first();

        $supcat = DB::table('product')
            ->where('slug', $sup_slug)
            ->first();
    
        if (!$category || !$subcat || !$supcat) {
            abort(404, 'Category or Subcategory or Supercategory not found');
        }
    
        $cid = $category->id;
        
        $sid = $subcat->id;

        $supid = $supcat->id;
    
        $supcategories = DB::table('product')
            ->where('status', 1)
            ->where('cat_id', $cid)
            ->get();

            $sub = $supcat->sub_id;

            $units = DB::table('unit')
            ->where('sub_id', $sub)
            ->where('status',1)
            ->get();
    
        $products = DB::table('super')
            ->where('status', 1)
            ->where('cat_id', $cid)
            ->where('sub_id', $sid)
            ->where('super_id', $supid)
            ->get();
          
            $productCount = $products->count();

            if ($productCount === 0) {
                $products = $supcat;
                $message = 'No data found';
                return view('frontend.product-detail', compact('supcategories','category', 'products','sid', 'sub_slug','units', 'cat_slug' , 'message', 'sup_slug'));
            }
        
        return view('frontend.product', compact('supcategories', 'units','products', 'sid','sub_slug', 'cat_slug', 'sup_slug'));
    }
    


    public function productdetailsearch($slug){
      

        $products = DB::table('product')
        ->where('status',1)
        ->first();
        
        

        $super= $products->super_id;

        $category_id = $products->cat_id;

        $superproducts = DB::table('super')
        ->where('id', $super)
        ->where('status',1)
        ->first();
        
        $category = DB::table('category')
        ->where('id', $category_id)
        ->where('status',1)
        ->first();



        $sid= $products->sub_id;

        $units = DB::table('unit')
        ->where('sub_id', $sid)
        ->where('status',1)
        ->get();


        return view('frontend/product-detail' , compact('products', 'units','category', 'superproducts','sid'));
    }

    
    public function product_detail($slug){
        $superproducts = DB::table('super')
        ->where('slug', $slug)
        ->where('status',1)
        ->first();

       

        $super= $superproducts->super_id;

        $products = DB::table('product')
        ->where('id', $super)
        ->where('status',1)
        ->first();

        $category_id = $superproducts->cat_id;

        $category = DB::table('category')
        ->where('id', $category_id)
        ->where('status',1)
        ->first();

        $sub= $superproducts->sub_id;
        $sid= $superproducts->sub_id;

        $units = DB::table('unit')
        ->where('sub_id', $sub)
        ->where('status',1)
        ->get();



        return view('frontend/product-detail' , compact('products','sid', 'units','category', 'superproducts'));
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

 $latitude = $request->latitude;
 $subcategory_get = $request->subcategory_check;
 $subcategory_get_ids = DB::table('sub')
 ->where('id', $subcategory_get)
 ->where('status', 1)
 ->first();

 
 $longitude = $request->longitude;
 
 $bidArea = $request->bid_area;
 $subcategory_get_id = $subcategory_get_ids->title;
if ($bidArea == 5000) {
    $sellers = DB::table('seller')->whereRaw("FIND_IN_SET(?, pro_ser)", [$subcategory_get_id])
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
                  ->whereRaw("FIND_IN_SET(?, pro_ser)", [$subcategory_get_id])
     ->havingRaw("distance < ?", [$bidArea]) 
     ->get();
}
    $sellerIds = $sellers->pluck('id')->implode(',');

 $data = [
     'latitude' => $latitude,   
     'longitude' => $longitude,
     'name' => $request->name,
     'email' => $request->email,
     'city' => $request->city,
     'address' => $request->address,
     'state' => $request->state,
     'zipcode' => $request->zipcode,
     'unit' => $request->unit,
     'message' => $request->message,
     'bid_area' => $bidArea, 
     'bid_time' => $request->bid_time,
     'material' => $request->material,
     'product_brand' => $request->product_brand,
     'product_name' => $request->product_name,
     'quantity' => $request->quantity,
     'product_id' => $request->product_id,
     'product_img' => $request->product_img,
     'seller_id' => $sellerIds,
     'cat_id' => $request->cat_id,
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


    public function rating(Request $request)
{


 $name = $request->session()->get('seller')->name;
 $star = $request->star;
 $review = $request->review;
 $email = $request->email;
 

 $data = [
    
     'name' => $name,
     'email' => $email,
     'star' => $star,
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

        // Fetch subcategories based on selected categories and active status
        $subcategories = DB::table('sub')
            ->whereIn('cat_id', $categories)
            ->where('status', 1)
            ->get();

        $options = '';
        foreach ($subcategories as $sub) {
            // Creating checkboxes instead of option tags
            $options .= '<label><input type="checkbox" name="pro_ser[]" value="'.$sub->title.'"> '.$sub->title.'</label><br>';
        }

        return response()->json($options);
    }

    // Return a message if no categories are selected
    return response()->json('<p>No subcategories available</p>');
}
public function fetchOptionsback(Request $request)
{
    if ($request->has('categories')) {
        $categories = $request->input('categories');

        // Get pro_ser from session
        $pro_ser = session()->get('seller')->pro_ser ?? ''; 

        // Convert to array (if comma-separated)
        $selectedServices = explode(',', $pro_ser);

        // Fetch subcategories based on selected categories and active status
        $subcategories = DB::table('sub')
            ->whereIn('cat_id', $categories)
            ->where('status', 1)
            ->get();

        $options = '';
        foreach ($subcategories as $sub) {
            // Check if this subcategory title is in the selected services array
            $checked = in_array($sub->title, $selectedServices) ? 'checked' : '';

            // Creating checkboxes
            $options .= '<label><input type="checkbox" name="pro_ser[]" value="'.$sub->title.'" '.$checked.'> '.$sub->title.'</label><br>';
        }

        return response()->json($options);
    }

    // Return a message if no categories are selected
    return response()->json('<p>No subcategories available</p>');
}



// public function fetchOptions(Request $request)
//     {
//         if ($request->has('categories')) {
//             $categories = $request->input('categories');
            
//             $subcategories = DB::table('sub')
//                 ->whereIn('cat_id', $categories)
//                 ->where('status', 1)
//                 ->get();
                
//             $options = '<option value="">Select</option>';
//             foreach ($subcategories as $sub) {
//                 $options .= '<option value="'.$sub->title.'">'.$sub->title.'</option>';
//             }
            
//             return response()->json($options);
//         }
        
//         return response()->json('<option value="">Select</option>');
//     }

    // forget


    
    public function forgot_password(){
        return view('frontend.forgot-password');
    }

    public function c_update(Request $request){
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

    $password = $request->password;
    $hashedPassword = bcrypt($password);

    

    $updated = DB::table('seller')->where('hash_id', $hash_id)->update(['password' => $hashedPassword]);

    if ($updated) {
        return back()->with('success', 'Password has been updated successfully.');
    } else {
        return redirect()->back()->withErrors(['error' => 'Update failed.'])->withInput();
    }
}



public function search(Request $request)
{
    $keyword = $request->search;
    $category = $request->category;
    
    $products = DB::table('product')
        ->leftJoin('category', 'product.cat_id', '=', 'category.id')
        ->leftJoin('sub', 'product.sub_id', '=', 'sub.id')
        ->leftJoin('super', 'product.super_id', '=', 'super.id')
        ->select(
            'product.*',
            'category.*',
            'product.image as img',
            'product.id as product_id',
            'product.title as product_title',
            'product.image as product_image',
            'product.slug as product_slug',
            'product.status as product_status',
            'category.id as category_id',
            'category.title as category_title',
            'sub.id as sub_id',
            'sub.title as sub_title',
            'super.title as super_title'
        );

    if ($keyword) {
        $products->where('product.title', 'like', '%' . $keyword . '%');
    }
    if ($category) {
        $products->Where('category.id', $category);
    }

    $products = $products->get();
    $productCount = $products->count();

 $data = [
            'keyword' => $keyword,
            'category' => $category,
        ];

    if ($productCount === 0) {
        $message = 'No data found';
        return view('frontend.search', compact('message' , 'data')); // Include other variables if needed
    }

   
    return view('frontend.search', compact('products', 'data'));
}




public function quotationlist(Request $request)
        {
        
            $keyword = $request->input('keyword');
            $category = $request->input('category');
            $date = $request->input('date');
            $city = $request->input('city');
            $quantity = $request->input('quantity');
            $product_name = $request->input('product_name');
            $recordsPerPage = $request->input('r_page', 15);
            $currentDate = \Carbon\Carbon::now();
            $category_data=DB::table('category')->get();
         
            $query = DB::table('qutation_form')
            ->leftJoin('seller', 'qutation_form.email', '=', 'seller.email')
            ->leftJoin('product', 'qutation_form.product_id', '=', 'product.id')
            ->leftJoin('sub', 'product.sub_id', '=', 'sub.id')
            ->leftJoin('category', 'sub.cat_id', '=', 'category.id')  
            ->whereRaw("DATE_ADD(date_time, INTERVAL bid_time DAY) >= ?", [$currentDate])
            ->select(
                // qutation_form columns
                'qutation_form.id as id',
                'qutation_form.name as name',
                'qutation_form.email as email',
                'qutation_form.product_id as qutation_form_product_id',
                'qutation_form.product_img as qutation_form_product_img',
                'qutation_form.product_name	 as qutation_form_product_name',
                'qutation_form.product_brand	 as qutation_form_product_brand',
                'qutation_form.message	 as qutation_form_message',
                'qutation_form.location	 as qutation_form_location',
                'qutation_form.address	 as qutation_form_address',
                'qutation_form.zipcode	 as qutation_form_zipcode',
                'qutation_form.state	 as qutation_form_state',
                'qutation_form.city	 as qutation_form_city',
                'qutation_form.bid_area	 as qutation_form_bid_area',
                'qutation_form.date_time	 as date_time',
                'qutation_form.bid_time	 as bid_time',
                'qutation_form.material	 as qutation_form_material',
                'qutation_form.image	 as qutation_form_image',
                'qutation_form.latitude	 as qutation_form_latitude',
                'qutation_form.longitude	 as qutation_form_longitude',
                'qutation_form.seller_id	 as qutation_form_seller_id',
                'qutation_form.unit	 as unit',
                'qutation_form.quantity	 as quantity',
                'qutation_form.status	 as qutation_form_status',

                // seller columns
                'seller.id as seller_id',
                'seller.email as seller_email',
                'seller.name as seller_name',
                'seller.phone as seller_phone',
                'seller.hash_id as seller_hash_id',
                'seller.pro_ser as seller_pro_ser',

                // product columns
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

                // sub columns
                'sub.id as sub_id',
                'sub.title as sub_name',
                'sub.cat_id as sub_cat_id',
                'sub.post_date as sub_post_date',
                'sub.image as sub_image',
                'sub.slug as sub_slug',
                'sub.status as sub_status',
                'sub.order_by as sub_order_by',

                // category columns
                'category.id as category_id',
                'category.title as category_name',
                'category.post_date as category_post_date',
                'category.image as category_image',
                'category.slug as category_slug',
                'category.status as category_status'

            );



        if($category){
             $query->where('category.id', 'like', '%' . $category . '%');
            }
            
        if ($date) {
           $query->where('date_time', 'like', '%' . $date . '%');
        }

        if($city){
           $query->where('city', 'like', '%' . $city . '%');
        }
        if($quantity){
           $query->where('quantity', 'like', '%' . $quantity . '%');
        }
        if($product_name){
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

            return view('admin.enquiry.list', compact('blogs', 'data','category_data'));
        }










        public function deactivelist(Request $request)
        {
           
            $keyword = $request->input('keyword');
            $category = $request->input('category');
            $date = $request->input('date');
            $city = $request->input('city');
            $quantity = $request->input('quantity');
            $product_name = $request->input('product_name');
            $recordsPerPage = $request->input('r_page', 15);
            $currentDate = \Carbon\Carbon::now();
            $category_data=DB::table('category')->get();
         
            $query = DB::table('qutation_form')
            ->leftJoin('seller', 'qutation_form.email', '=', 'seller.email')
            ->leftJoin('product', 'qutation_form.product_id', '=', 'product.id')
            ->leftJoin('sub', 'product.sub_id', '=', 'sub.id')
            ->leftJoin('category', 'sub.cat_id', '=', 'category.id')  
            ->whereRaw("DATE_ADD(date_time, INTERVAL bid_time DAY) <= ?", [$currentDate])
            ->select(
                // qutation_form columns
                'qutation_form.id as id',
                'qutation_form.name as name',
                'qutation_form.email as email',
                'qutation_form.product_id as qutation_form_product_id',
                'qutation_form.product_img as qutation_form_product_img',
                'qutation_form.product_name	 as qutation_form_product_name',
                'qutation_form.product_brand	 as qutation_form_product_brand',
                'qutation_form.message	 as qutation_form_message',
                'qutation_form.location	 as qutation_form_location',
                'qutation_form.address	 as qutation_form_address',
                'qutation_form.zipcode	 as qutation_form_zipcode',
                'qutation_form.state	 as qutation_form_state',
                'qutation_form.city	 as qutation_form_city',
                'qutation_form.bid_area	 as qutation_form_bid_area',
                'qutation_form.date_time	 as date_time',
                'qutation_form.bid_time	 as bid_time',
                'qutation_form.material	 as qutation_form_material',
                'qutation_form.image	 as qutation_form_image',
                'qutation_form.latitude	 as qutation_form_latitude',
                'qutation_form.longitude	 as qutation_form_longitude',
                'qutation_form.seller_id	 as qutation_form_seller_id',
                'qutation_form.unit	 as unit',
                'qutation_form.quantity	 as quantity',
                'qutation_form.status	 as qutation_form_status',

                // seller columns
                'seller.id as seller_id',
                'seller.email as seller_email',
                'seller.name as seller_name',
                'seller.phone as seller_phone',
                'seller.hash_id as seller_hash_id',
                'seller.pro_ser as seller_pro_ser',

                // product columns
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

                // sub columns
                'sub.id as sub_id',
                'sub.title as sub_name',
                'sub.cat_id as sub_cat_id',
                'sub.post_date as sub_post_date',
                'sub.image as sub_image',
                'sub.slug as sub_slug',
                'sub.status as sub_status',
                'sub.order_by as sub_order_by',

                // category columns
                'category.id as category_id',
                'category.title as category_name',
                'category.post_date as category_post_date',
                'category.image as category_image',
                'category.slug as category_slug',
                'category.status as category_status'

            );



        if($category){
             $query->where('category.id', 'like', '%' . $category . '%');
            }
            
        if ($date) {
           $query->where('date_time', 'like', '%' . $date . '%');
        }

        if($city){
           $query->where('city', 'like', '%' . $city . '%');
        }
        if($quantity){
           $query->where('quantity', 'like', '%' . $quantity . '%');
        }
        if($product_name){
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

            return view('admin.enquiry.deactivelist', compact('blogs', 'data','category_data'));
        }

        
    public function vewsell($id){
        // $query = DB::table('qutation_form')->where('id' , $id)->first();

       

        $query = DB::table('qutation_form')
                ->leftJoin('seller', 'qutation_form.email', '=', 'seller.email')
                ->leftJoin('product', 'qutation_form.product_id', '=', 'product.id')
                ->leftJoin('sub', 'product.sub_id', '=', 'sub.id')
                ->leftJoin('category', 'sub.cat_id', '=', 'category.id')
                ->where('qutation_form.id', $id)
                ->select(
                    // qutation_form columns
                    'qutation_form.id as qutation_form_id',
                    'qutation_form.name as qutation_form_name',
                    'qutation_form.email as qutation_form_email',
                    'qutation_form.product_id as qutation_form_product_id',
                    'qutation_form.product_img as qutation_form_product_img',
                    'qutation_form.product_name	 as qutation_form_product_name',
                    'qutation_form.product_brand	 as qutation_form_product_brand',
                    'qutation_form.message	 as qutation_form_message',
                    'qutation_form.location	 as qutation_form_location',
                    'qutation_form.address	 as qutation_form_address',
                    'qutation_form.zipcode	 as qutation_form_zipcode',
                    'qutation_form.state	 as qutation_form_state',
                    'qutation_form.city	 as qutation_form_city',
                    'qutation_form.bid_area	 as qutation_form_bid_area',
                    'qutation_form.bid_time	 as qutation_form_bid_time',
                    'qutation_form.material	 as qutation_form_material',
                    'qutation_form.image	 as qutation_form_image',
                    'qutation_form.latitude	 as qutation_form_latitude',
                    'qutation_form.longitude	 as qutation_form_longitude',
                    'qutation_form.seller_id	 as qutation_form_seller_id',
                    'qutation_form.unit	 as qutation_form_date_unit',
                    'qutation_form.quantity	 as qutation_form_quantity',
                    'qutation_form.status	 as qutation_form_status',

                    // seller columns
                    'seller.id as seller_id',
                    'seller.email as seller_email',
                    'seller.name as seller_name',
                    'seller.phone as seller_phone',
                    'seller.hash_id as seller_hash_id',
                    'seller.pro_ser as seller_pro_ser',

                    // product columns
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

                    // sub columns
                    'sub.id as sub_id',
                    'sub.title as sub_name',
                    'sub.cat_id as sub_cat_id',
                    'sub.post_date as sub_post_date',
                    'sub.image as sub_image',
                    'sub.slug as sub_slug',
                    'sub.status as sub_status',
                    'sub.order_by as sub_order_by',

                    // category columns
                    'category.id as category_id',
                    'category.title as category_name',
                    'category.post_date as category_post_date',
                    'category.image as category_image',
                    'category.slug as category_slug',
                    'category.status as category_status'

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
    
            // Retrieve form data
            $name = $request->input('name');
            $email = $request->input('email');
            $phone = $request->input('phone');
            $message = $request->input('message');
            $service = $request->input('service');
         
    
            $data = [
                'Name' => $name,
                'Email' => $email,
                'Phone' => $phone,
                'Service' => $service,
                'Message' => $message,
               
            ];
    
            // Send email
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
            // Retrieve form data
            $name = $request->input('name');
            $email = $request->input('email');
            $phone = $request->input('phone');
            $message = $request->input('message');
            $service = $request->input('service');
         
    
            $data = [
                'Name' => $name,
                'Email' => $email,
                'Phone' => $phone,
                'Service' => $service,
                'Message' => $message,
               
            ];
    
            // Send email
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

        $keyword = $request->input('keyword');
        $category = $request->input('category');
        $date = $request->input('date');
        $city = $request->input('city');
        $quantity = $request->input('quantity');
        $product_name = $request->input('product_name');

        $category_data=DB::table('category')->get();
        $recordsPerPage = $request->input('r_page', 25);
        

        $data = DB::table('bidding_price')
        
        ->leftJoin('seller', 'bidding_price.seller_email', '=', 'seller.email')
        ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')
        ->leftJoin('sub', 'product.sub_id', '=', 'sub.id')
        ->leftJoin('category', 'sub.cat_id', '=', 'category.id')
        ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
        ->where('bidding_price.payment_status','success')
        ->where('bidding_price.action','1')
        ->where('bidding_price.hide','1')
        ->orderBy('bidding_price.id', 'desc')
        ->select(
            // bidding_price columns
            'bidding_price.id as bidding_price_id',
                            'bidding_price.rate as rate',
                            'bidding_price.price as price',

            'bidding_price.seller_email as seller_email',
            'bidding_price.product_id as bidding_price_product_id',
            'bidding_price.product_name as bidding_price_product_name',
            'bidding_price.payment_status as bidding_price_payment_status',
            'bidding_price.filename as bidding_price_filename',
            


            // qf
                    'qutation_form.bid_time as bid_time',
                    'qutation_form.material as material',
                    'qutation_form.id as id',
                    'qutation_form.name as name',
                    'qutation_form.email as email',
                    'qutation_form.product_id as qutation_form_product_id',
                    'qutation_form.product_img as qutation_form_product_img',
                    // 'qutation_form.product_name	as qutation_form_product_name',
                    'qutation_form.product_brand as qutation_form_product_brand',
                    'qutation_form.message as qutation_form_message',
                    // 'qutation_form.location	as qutation_form_location',
                    'qutation_form.address as qutation_form_address',
                    'qutation_form.zipcode as qutation_form_zipcode',
                    'qutation_form.state as qutation_form_state',
                    'qutation_form.city as qutation_form_city',
                    'qutation_form.date_time as date_time',
                    // 'qutation_form.bid_time	as bid_time',
                    'qutation_form.image as qutation_form_image',
                    'qutation_form.latitude as qutation_form_latitude',
                    'qutation_form.longitude as qutation_form_longitude',
                    'qutation_form.seller_id as qutation_form_seller_id',
                    'qutation_form.unit	 as unit',
                    'qutation_form.quantity as quantity',
                    'qutation_form.status as qutation_form_status',

            // seller columns
            'seller.id as seller_id',
            'seller.email as seller_email',
            'seller.name as seller_name',
            'seller.phone as seller_phone',
            'seller.hash_id as seller_hash_id',
            'seller.pro_ser as seller_pro_ser',

            // product columns
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

            // sub columns
            'sub.id as sub_id',
            'sub.title as sub_name',
            'sub.cat_id as sub_cat_id',
            'sub.post_date as sub_post_date',
            'sub.image as sub_image',
            'sub.slug as sub_slug',
            'sub.status as sub_status',
            'sub.order_by as sub_order_by',

            // category columns
            'category.id as category_id',
            'category.title as category_name',
            'category.post_date as category_post_date',
            'category.image as category_image',
            'category.slug as category_slug',
            'category.status as category_status'

        );

        if($category){
            $data->where('category.id', 'like', '%' . $category . '%');
        }
        if($product_name){
            $data->where('product.title', 'like', '%' . $product_name . '%');
        }
        
    
       if ($date) {
          $data->where('qutation_form.date_time', 'like', '%' . $date . '%');
       }

       if($city){
          $data->where('qutation_form.city', 'like', '%' . $city . '%');
       }
       if($quantity){
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
            



        $total=$data->count();

        return view('admin/enquiry/acceptedbidding',  compact('data','datas','total','category_data'));
    }

    public function totalbidding(Request $request){

        $keyword = $request->input('keyword');
        $category = $request->input('category');
        $date = $request->input('date');
        $city = $request->input('city');
        $quantity = $request->input('quantity');
        $product_name = $request->input('product_name');

        $category_data=DB::table('category')->get();
        $recordsPerPage = $request->input('r_page', 25);
        

        $data = DB::table('bidding_price')
        
        ->leftJoin('seller', 'bidding_price.seller_email', '=', 'seller.email')
        ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')
        ->leftJoin('sub', 'product.sub_id', '=', 'sub.id')
        ->leftJoin('category', 'sub.cat_id', '=', 'category.id')
        ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
        ->where('bidding_price.payment_status','success')
        ->orderBy('bidding_price.id', 'desc')
        ->select(
            // bidding_price columns
            'bidding_price.id as bidding_price_id',
                            'bidding_price.rate as rate',
                            'bidding_price.price as price',

            'bidding_price.seller_email as seller_email',
            'bidding_price.product_id as bidding_price_product_id',
            'bidding_price.product_name as bidding_price_product_name',
            'bidding_price.payment_status as bidding_price_payment_status',
            'bidding_price.filename as bidding_price_filename',
            


            // qf
                    'qutation_form.bid_time as bid_time',
                    'qutation_form.material as material',
                    'qutation_form.id as id',
                    'qutation_form.name as name',
                    'qutation_form.email as email',
                    'qutation_form.product_id as qutation_form_product_id',
                    'qutation_form.product_img as qutation_form_product_img',
                    // 'qutation_form.product_name	as qutation_form_product_name',
                    'qutation_form.product_brand as qutation_form_product_brand',
                    'qutation_form.message as qutation_form_message',
                    // 'qutation_form.location	as qutation_form_location',
                    'qutation_form.address as qutation_form_address',
                    'qutation_form.zipcode as qutation_form_zipcode',
                    'qutation_form.state as qutation_form_state',
                    'qutation_form.city as qutation_form_city',
                    'qutation_form.date_time as date_time',
                    // 'qutation_form.bid_time	as bid_time',
                    'qutation_form.image as qutation_form_image',
                    'qutation_form.latitude as qutation_form_latitude',
                    'qutation_form.longitude as qutation_form_longitude',
                    'qutation_form.seller_id as qutation_form_seller_id',
                    'qutation_form.unit	 as unit',
                    'qutation_form.quantity as quantity',
                    'qutation_form.status as qutation_form_status',

            // seller columns
            'seller.id as seller_id',
            'seller.email as seller_email',
            'seller.name as seller_name',
            'seller.phone as seller_phone',
            'seller.hash_id as seller_hash_id',
            'seller.pro_ser as seller_pro_ser',

            // product columns
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

            // sub columns
            'sub.id as sub_id',
            'sub.title as sub_name',
            'sub.cat_id as sub_cat_id',
            'sub.post_date as sub_post_date',
            'sub.image as sub_image',
            'sub.slug as sub_slug',
            'sub.status as sub_status',
            'sub.order_by as sub_order_by',

            // category columns
            'category.id as category_id',
            'category.title as category_name',
            'category.post_date as category_post_date',
            'category.image as category_image',
            'category.slug as category_slug',
            'category.status as category_status'

        );

        if($category){
            $data->where('category.id', 'like', '%' . $category . '%');
        }
        if($product_name){
            $data->where('product.title', 'like', '%' . $product_name . '%');
        }
        
    
       if ($date) {
          $data->where('qutation_form.date_time', 'like', '%' . $date . '%');
       }

       if($city){
          $data->where('qutation_form.city', 'like', '%' . $city . '%');
       }
       if($quantity){
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
            



        $total=$data->count();

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
            // Retrieve form data
            $name = $request->input('name');
            $email = $request->input('email');
            $phone = $request->input('phone');
            $message = $request->input('message');
         
    
            $data = [
                'Name' => $name,
                'Email' => $email,
                'Phone' => $phone,
                'Message' => $message,
               
            ];
    
            // Send email
            Mail::to($email)
                // ->bcc("lankesh3341@gmail.com")
                ->bcc("support@ursbid.com")
                ->send(new Support_mail($data));
               
                return redirect()->back()->withSuccess('Thank you for contacting us!');
               
              } catch (\Exception $e) {
                  Log::error('Error: ' . $e->getMessage());
                  Session::flash('error', 'An error occurred while processing. Please try again later. Error: ' . $e->getMessage());
                  return redirect()->back();
              }
    }





}