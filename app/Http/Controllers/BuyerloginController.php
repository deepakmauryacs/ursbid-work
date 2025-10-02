<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\Buyer;
use App\Http\Controllers\URSController\MyBiddingController;

class BuyerloginController extends Controller
{
    public function vewfile($id){
        $query = DB::table('qutation_form')->where('id' , $id)->first();
        $images = explode(',', $query->image);
        return view('/seller/buyer-order/file', compact('query','images'));
      
        
    }

    public function buyer_register()
    {
      return view('frontend/buyer-register');
    }
    public function buyer_create(Request $request)
    {
        $validator = $request->validate([
            'email'=>'required|unique:buyer',
            'name'=>'required',
            'phone'=>'required',
            'password'=>'required',
            'gender'=>'required',
            
        ]);
       
         $buyer = DB::table('buyer')->insert([
            'email' => $request->email, 
            'name' => $request->name, 
            'phone' => $request->phone, 
            'password' => $request->password, 
            'gender' => $request->gender,
                 
        ]);
    
        if($buyer)
               {
                return back()->with('success', 'Account Has Been Created successfully. Please Login Now.');
               }else{
                return back()->withErrors(['error' => 'Insertion Failed.'])->withInput();
               }
    
    }

















    public function buyer_login()
    {

      return view('frontend/buyer-login');
    }

    public function authenticate(Request $request)
    {
      $validator = $request->validate([
        'email' => 'required',
        'password' => 'required'
    ]);

    $buyer = Buyer::where('email', $request->email)->first();
    if ($buyer) {
        if ($request->password == $buyer->password) {
            $request->session()->put('buyer', $buyer);
            return redirect('/buyer-dashboard');
        } else {
            return back()->with([
                'alert' => 'Incorrect password'
            ])->withInput();
        }
    } else {
        return back()->with([
            'alert' => 'No account found for this email'
        ])->withInput();
    }
    //     else {
    //         $request->session()->flash('fail', 'Incorrect password');
    //         return redirect('/buyer-login')->withErrors($validator)->withInput();
    //     }
    // } else {
    //     $request->session()->flash('fail', 'No account found for this email');
    //     return redirect('/buyer-login')->withErrors($validator)->withInput();
      
    // }
    }
    public function authenticate2(Request $request)
    {
      $validator = $request->validate([
        'email' => 'required',
        'password' => 'required'
    ]);

    $buyer = Buyer::where('email', $request->email)->first();
    if ($buyer) {
        if ($request->password == $buyer->password) {
            $request->session()->put('buyer', $buyer);
            return back();
        } else {
            return back()->with([
                'alert' => 'Incorrect password'
            ])->withInput();
        }
    } else {
        return back()->with([
            'alert' => 'No account found for this email'
        ])->withInput();
    }
    }


    public function buyer_dashboard(Request $request){

        $keyword = $request->input('keyword');
        $category = $request->input('category');
        $date = $request->input('date');
        $city = $request->input('city');
        $quantity = $request->input('quantity');
        $product_name = $request->input('product_name');

        $category_data=DB::table('category')->get();
        $recordsPerPage = $request->input('r_page', 25);
        // Get all session data as an associative array
    // $allSessionData = $request->session()->all();
    // echo "<pre>";
    // // Print (dump) all session data and stop execution
    // print_r($allSessionData); die();
        $seller = $request->session()->get('seller');
        if (!$seller) {
            return redirect()->route('seller-login');
        }

        $buyer_email = $seller->email;
        // $data = DB::table('qutation_form')->where('email',  $buyer_email)->get();



        $data = DB::table('qutation_form')
        
        ->leftJoin('seller', 'qutation_form.email', '=', 'seller.email')
        ->leftJoin('product', 'qutation_form.product_id', '=', 'product.id')
        ->leftJoin('sub', 'product.sub_id', '=', 'sub.id')
        ->leftJoin('category', 'sub.cat_id', '=', 'category.id')
        ->where('qutation_form.email',$buyer_email)
        
        ->select(
                    'qutation_form.product_name as product_name',
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
            $data->where('qutation_form.cat_id', 'like', '%' . $category . '%');
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
       if($product_name){
          $data->where('qutation_form.product_name', 'like', '%' . $product_name . '%');
       }
   
       $data = $data->orderBy('qutation_form.id', 'desc')->paginate($recordsPerPage);
       $total=$data->count();
            
       $datas = [
        'keyword' => $keyword,
        'date' => $date,
        'city' => $city,
        'quantity' => $quantity,
        'category' => $category,
        'product_name' => $product_name,
        'r_page' => $recordsPerPage,
        
    ];


        

        return view('seller/buyer-order/list',  compact('datas' , 'data','total','category_data'));
    }
    public function buyer_dashboard_accounting (Request $request){

        $keyword = $request->input('keyword');
        $category = $request->input('category');
        $date = $request->input('date');
        $city = $request->input('city');
        $quantity = $request->input('quantity');
        $product_name = $request->input('product_name');

        $category_data=DB::table('category')->get();
        $recordsPerPage = $request->input('r_page', 25);
        


        $data = DB::table('qutation_form')
        
        ->leftJoin('seller', 'qutation_form.email', '=', 'seller.email')
        ->leftJoin('product', 'qutation_form.product_id', '=', 'product.id')
        ->leftJoin('sub', 'product.sub_id', '=', 'sub.id')
        ->leftJoin('category', 'sub.cat_id', '=', 'category.id')
       
        
        ->select(
                    'qutation_form.product_name as product_name',
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
            $data->where('qutation_form.cat_id', 'like', '%' . $category . '%');
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
       if($product_name){
          $data->where('qutation_form.product_name', 'like', '%' . $product_name . '%');
       }
   
       $data = $data->orderBy('qutation_form.id', 'desc')->paginate($recordsPerPage);
       $total=$data->count();
            
       $datas = [
        'keyword' => $keyword,
        'date' => $date,
        'city' => $city,
        'quantity' => $quantity,
        'category' => $category,
        'product_name' => $product_name,
        'r_page' => $recordsPerPage,
        
    ];


        

        return view('admin/buyer-order/list',  compact('datas' , 'data','total','category_data'));
    }


    public function mylist(Request $request)
    {
        return app(MyBiddingController::class)->index($request);
    }

    public function price_list(Request $request, $data_id){
        $seller = $request->session()->get('seller');
        if (!$seller) {
            return redirect()->route('seller-login');
        }
        $buyer_email = $seller->email;
        
      

        // $data = DB::table('bidding_price')
        // ->where('bidding_price.user_email', $buyer_email)
        // ->where('bidding_price.data_id', $data_id)
        // ->where('bidding_price.hide', '0')
        // ->join('seller', 'bidding_price.seller_email', '=', 'seller.email')
        // ->select('bidding_price.*', 'seller.phone', 'bidding_price.price as price', 'seller.id as seller_id', 'seller.email as seller_email', 'seller.phone as phone','seller.name as name')
        // ->get();
    
        $data = DB::table('bidding_price')
        
        ->leftJoin('seller', 'bidding_price.seller_email', '=', 'seller.email')
        ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')
        ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
        ->leftJoin('sub', 'product.sub_id', '=', 'sub.id')
        ->leftJoin('category', 'sub.cat_id', '=', 'category.id')
        ->where('bidding_price.user_email', $buyer_email)
        ->where('bidding_price.data_id', $data_id)
        ->where('bidding_price.hide', '0')
        ->where('bidding_price.payment_status','success')

        ->select(
            // bidding_price columns
            'bidding_price.id as bidding_price_id',
            'bidding_price.price as price',
            'bidding_price.action as action',
            'bidding_price.data_id as data_id',
                            'bidding_price.rate as rate',

           
            'bidding_price.seller_email as seller_email',
            'bidding_price.product_id as bidding_price_product_id',
            
            'bidding_price.product_name	 as bidding_price_product_name',
            'bidding_price.filename	 as bidding_price_filename',
            
                    //qf
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

        )->get();
    
        $total=$data->count();
       

        return view('seller/buyer-order/view',  compact('data','total'));
    }
    public function price_list_accounting(Request $request, $data_id){
        

        // $data = DB::table('bidding_price')
        // ->where('bidding_price.user_email', $buyer_email)
        // ->where('bidding_price.data_id', $data_id)
        // ->where('bidding_price.hide', '0')
        // ->join('seller', 'bidding_price.seller_email', '=', 'seller.email')
        // ->select('bidding_price.*', 'seller.phone', 'bidding_price.price as price', 'seller.id as seller_id', 'seller.email as seller_email', 'seller.phone as phone','seller.name as name')
        // ->get();
    
        $data = DB::table('bidding_price')
        
        ->leftJoin('seller', 'bidding_price.seller_email', '=', 'seller.email')
        ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')
        ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
        ->leftJoin('sub', 'product.sub_id', '=', 'sub.id')
        ->leftJoin('category', 'sub.cat_id', '=', 'category.id')
        
        ->where('bidding_price.data_id', $data_id)

        ->select(
            // bidding_price columns
            'bidding_price.id as bidding_price_id',
            'bidding_price.price as price',
            'bidding_price.action as action',
            'bidding_price.data_id as data_id',
                            'bidding_price.rate as rate',

           
            'bidding_price.seller_email as seller_email',
            'bidding_price.product_id as bidding_price_product_id',
            
            'bidding_price.product_name	 as bidding_price_product_name',
            'bidding_price.filename	 as bidding_price_filename',
            
                    //qf
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

        )->get();
    
        $total=$data->count();
       

        return view('admin/buyer-order/view',  compact('data','total'));
    }


    public function accepted_list(Request $request, $data_id){
        $seller = $request->session()->get('seller');
        if (!$seller) {
            return redirect()->route('seller-login');
        }
        $buyer_email = $seller->email;
        
      

        // $data = DB::table('bidding_price')
        // ->where('bidding_price.user_email', $buyer_email)
        // ->where('bidding_price.data_id', $data_id)
        // ->where('bidding_price.hide', '1')
        // ->where('bidding_price.action', '1')
        // ->join('seller', 'bidding_price.seller_email', '=', 'seller.email')
        // ->select('  .*', 'seller.phone', 'bidding_price.price as price', 'seller.id as seller_id', 'seller.email as seller_email', 'seller.phone as phone','seller.name as name')
        // ->get();
    
        $data = DB::table('bidding_price')
        
        ->leftJoin('seller', 'bidding_price.seller_email', '=', 'seller.email')
        ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')
        ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
        ->leftJoin('sub', 'product.sub_id', '=', 'sub.id')
        ->leftJoin('category', 'sub.cat_id', '=', 'category.id')
        ->where('bidding_price.user_email',$buyer_email)
        ->where('bidding_price.data_id', $data_id)
        ->where('bidding_price.hide', '1')
        ->where('bidding_price.action', '1')
        ->select(
            // bidding_price columns
            'bidding_price.id as id',
            'bidding_price.price as price',
            'bidding_price.action as action',
            'bidding_price.data_id as data_id',
                            'bidding_price.rate as rate',

           
            'bidding_price.seller_email as seller_email',
            'bidding_price.product_id as bidding_price_product_id',
            
            'bidding_price.product_name	 as bidding_price_product_name',
            
//qf
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

        )->get();
        
    
        $total=$data->count();
       

        return view('seller/buyer-order/accepted-list',  compact('data','total'));
    }

    public function accepted_list_accounting(Request $request, $data_id){
       
      
    
        $data = DB::table('bidding_price')
        
        ->leftJoin('seller', 'bidding_price.seller_email', '=', 'seller.email')
        ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')
        ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
        ->leftJoin('sub', 'product.sub_id', '=', 'sub.id')
        ->leftJoin('category', 'sub.cat_id', '=', 'category.id')
        ->where('bidding_price.data_id', $data_id)
        ->where('bidding_price.hide', '1')
        ->where('bidding_price.action', '1')
        ->select(
            // bidding_price columns
            'bidding_price.id as id',
            'bidding_price.price as price',
            'bidding_price.action as action',
            'bidding_price.data_id as data_id',
                            'bidding_price.rate as rate',

           
            'bidding_price.seller_email as seller_email',
            'bidding_price.product_id as bidding_price_product_id',
            
            'bidding_price.product_name	 as bidding_price_product_name',
            
//qf
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

        )->get();
        
    
        $total=$data->count();
       

        return view('admin/buyer-order/accepted-list',  compact('data','total'));
    }



    public function seller_profile($id){
        $previousUrl = url()->previous();
        $data12 = DB::table('seller')->where('id', $id)->first();
        $email = $data12->email;
        $reviews = DB::table('rating')->where('email', $email)->get();
        $rating = $reviews->average('star');
        $ratingPercentage = ($rating / 5) * 100;

      return view('/frontend/seller-profile', compact('data12', 'ratingPercentage','reviews','previousUrl'));  

    }
   

}