<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpVerificationMail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SellerloginController extends Controller
{
    public function seller_register()
    {
        return view('frontend/seller-register');
    }

    public function update_account($id)
    {
        $blog = DB::table('seller')
            ->where('hash_id', $id)
            ->first();
        return view('/seller/delete/update', compact('blog'));
    }

    public function update_details(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'gst' => 'required',
            'phone' => 'required',
            'acc_type' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please correct the highlighted errors and try again.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $acc_type = is_array($request->acc_type) ? implode(',', $request->acc_type) : $request->acc_type;
        $pro_ser = is_array($request->pro_ser) ? implode(',', $request->pro_ser) : "";

        $updateData = [
            'name' => $request->name,
            'gst' => $request->gst,
            'phone' => $request->phone,
            'acc_type' => $acc_type,
            'pro_ser' => $pro_ser,
        ];

        $updated = DB::table('seller')
            ->where('id', $id)
            ->update($updateData);

        $newData = DB::table('seller')
            ->where('id', $id)
            ->first();

        if ($newData) {
            $request->session()->put('seller', $newData);
        }

        if ($request->ajax()) {
            if ($updated === false) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Update failed. Please try again.',
                ], 500);
            }

            $message = $updated ? 'Account details updated successfully.' : 'No changes were necessary.';

            return response()->json([
                'status' => 'success',
                'message' => $message,
            ]);
        }

        if ($updated === false) {
            return back()
                ->withErrors(['error' => 'Update failed.'])
                ->withInput();
        }

        $message = $updated ? 'updated successfully.' : 'No changes were necessary.';

        return back()->with('success', $message);
    }

    public function delete_account(Request $request)
    {
        $otp = strval(rand(100000, 999999));
        $request->session()->put('seller_acc_delete_otp', $otp);
        $selleremail = $request->session()->get('seller')->email;
        $seller = DB::table('seller')
            ->where('email', $selleremail)
            ->first();
        $phone = $seller->phone ?? null;
        Mail::send([], [], function ($message) use ($otp, $selleremail) {
            $message
                ->to($selleremail)
                ->subject('Confirm Otp')
                ->html("Confirm Your otp to delete your account.<br>OTP: $otp");
        });

        // Send OTP via MSG91 OTP API v5
        if ($phone) {
            $authKey = "446194AThF7RkYkZ687de9a2P1"; // Your actual MSG91 auth key
            $templateId = "6888b559d6fc054fb8040433"; // Approved template ID with ##otp##

            $smsPayload = [
                "template_id" => $templateId,
                "mobile" => "91" . $phone,
                "otp" => $otp,
            ];

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://control.msg91.com/api/v5/otp",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($smsPayload),
                CURLOPT_HTTPHEADER => ["Content-Type: application/json", "authkey: $authKey"],
            ]);

            $response = curl_exec($curl);
            curl_close($curl);
        }

        return view('seller/delete/add');
    }

    public function delete_acc(Request $request)
    {
        $otp = $request->otp;
        $selleremail = $request->session()->get('seller')->email;

        $sellerotp = $request->session()->get('seller_acc_delete_otp');

        if ($sellerotp == $otp) {
            DB::table('seller')
                ->where('email', $selleremail)
                ->delete();
            $request->session()->pull('seller');
            return redirect('seller-register');
        } else {
            return back()->with('error', 'OTP verification failed.');
        }
    }

    public function refer_register($id)
    {
        return view('frontend/refer-register', ['refer_id' => $id]);
    }

    public function showOtpVerificationForm($hash_id)
    {
        return view('frontend/verify-otp', ['hashId' => $hash_id]);
    }

    public function seller_create(Request $request)
    {
        $validator = $request->validate(
            [
                'email' => 'required',
                'name' => 'required',
                'phone' => 'required',
                'g-recaptcha-response' => 'required',
                'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z\d])[A-Za-z\d\W]{8,}$/'],
            ],
            [
                'password.required' => 'Password field is required.',
                'password.min' => 'Password must be at least 8 characters long.',
                'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).',
            ]
        );

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        $responseBody = $response->json();

        if (!$responseBody['success']) {
            return back()
                ->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed'])
                ->withInput();
        }
        if ($request->referral_code) {
            $existcode = DB::table('seller')
                ->where('ref_code', $request->referral_code)
                ->first();

            if (!$existcode) {
                return back()
                    ->withErrors(['ref_error' => 'Sorry, Referral Code is wrong.'])
                    ->withInput();
            }
        }

        $existingSeller = DB::table('seller')
            ->where('email', $request->email)
            ->first();

        if ($existingSeller) {
            if ($existingSeller->verify == 1) {
                return back()
                    ->withErrors(['email' => 'Sorry, Email already exists.'])
                    ->withInput();
            } else {
                // Email exists but is not verified, resend the OTP email
                $otp = strval(rand(100000, 999999));
                $hashId = Str::random(32);

                $ref = Str::random(5);
                $random_numbers = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
                $ref_code = strtoupper($ref . $random_numbers);

                $otp = strval(rand(100000, 999999));

                $acc_type = is_array($request->acc_type) ? implode(',', $request->acc_type) : $request->acc_type;

                $pro_ser = is_array($request->pro_ser) ? implode(',', $request->pro_ser) : "";

                // Update the existing record with new OTP and hash_id
                DB::table('seller')
                    ->where('id', $existingSeller->id)
                    ->update([
                        'otp' => $otp,
                        'hash_id' => $hashId,
                        'email' => $request->email,
                        'name' => $request->name,
                        'phone' => $request->phone,
                        'password' => Hash::make($request->password),
                        'buyer' => $request->buyer,
                        'gst' => $request->gst,
                        'contractor' => $request->contractor,
                        'client' => $request->client,
                        'seller' => $request->seller,
                        'ref_code' => $ref_code,
                        'ref_by' => $request->referral_code,
                        'acc_type' => $acc_type,
                        'pro_ser' => $pro_ser,
                    ]);

                $this->sendOtpEmail($request->email, $otp, $hashId);
                $this->sendOtpSMS($request->phone, $otp);
                return redirect()->route('verify.otp', ['hash_id' => $hashId]);
            }
        }

        $ref = Str::random(5);
        $random_numbers = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
        $ref_code = strtoupper($ref . $random_numbers);

        $otp = strval(rand(100000, 999999));
        // $acc_type = implode(',', $request->acc_type);
        // $pro_ser = implode(',', $request->pro_ser);

        $acc_type = is_array($request->acc_type) ? implode(',', $request->acc_type) : $request->acc_type;

        $pro_ser = is_array($request->pro_ser) ? implode(',', $request->pro_ser) : "";

        $sellerId = DB::table('seller')->insertGetId([
            'email' => $request->email,
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'buyer' => $request->buyer,
            'gst' => $request->gst,
            'contractor' => $request->contractor,
            'client' => $request->client,
            'seller' => $request->seller,
            'ref_code' => $ref_code,
            'ref_by' => $request->referral_code,
            'otp' => $otp,
            'acc_type' => $acc_type,
            'pro_ser' => $pro_ser,
        ]);

        if ($sellerId) {
            $hashId = Str::random(32);

            DB::table('seller')
                ->where('id', $sellerId)
                ->update(['hash_id' => $hashId]);

            $this->sendOtpEmail($request->email, $otp, $hashId);
            return redirect()->route('verify.otp', ['hash_id' => $hashId]);
        } else {
            return back()
                ->withErrors(['error' => 'Insertion Failed.'])
                ->withInput();
        }
    }

    private function sendOtpEmail($email, $otp, $hashId)
    {
        Mail::to($email)->send(new OtpVerificationMail($otp, $hashId));
    }

    private function sendOtpSMS($mobile, $otp)
    {
        $authKey = "446194AThF7RkYkZ687de9a2P1"; //  Aapka actual auth key
        $senderId = "URSBID"; //  Approved sender ID
        $templateId = "6888b559d6fc054fb8040433"; //  Your template ID

        //  Debug log to confirm OTP length
        Log::info("Sending OTP to mobile: 91$mobile | OTP: $otp");

        $data = [
            'authkey' => $authKey,
            'sender' => $senderId,
            'route' => '4',
            'mobiles' => '91' . $mobile,
            'template_id' => $templateId,
            'otp' => $otp,
        ];

        $response = Http::asForm()->post("https://api.msg91.com/api/v5/otp", $data);

        if (!$response->successful()) {
            Log::error('MSG91 OTP SMS Error: ' . $response->body());
        } else {
            Log::info('MSG91 OTP SMS Sent: ' . $response->body()); //  Success log
        }
    }

    public function verifyOtp(Request $request)
    {
        $otp = implode('', $request->otp);

        $seller = DB::table('seller')
            ->where('hash_id', $request->hash_id)
            ->where('otp', $otp)
            ->first();

        if ($seller) {
            $request->session()->put('seller', $seller);
            DB::table('seller')
                ->where('hash_id', $request->hash_id)
                ->update(['otp' => null, 'verify' => '1']);
            return redirect('/seller-dashboard')->with('email', $seller->email);
        } else {
            return back()
                ->withErrors(['error' => 'Invalid OTP Please try again'])
                ->withInput();
        }
    }

    public function authenticate2(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $buyer = Seller::where('email', $request->email)->first();
        if ($buyer) {
            if ($request->password == $buyer->password) {
                $request->session()->put('seller', $buyer);
                return back();
            } else {
                return back()
                    ->with([
                        'alert' => 'Incorrect password',
                    ])
                    ->withInput();
            }
        } else {
            return back()
                ->with([
                    'alert' => 'No account found for this email',
                ])
                ->withInput();
        }
    }

    public function seller_login()
    {
        return view('frontend/seller-login');
    }

    public function authenticate(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $seller = Seller::where('email', $request->email)
            ->where('verify', 1)
            ->where('status', 1)
            ->first();
        if ($seller) {
            if (Hash::check($request->password, $seller->password)) {
                $email = $request->email;

                $request->session()->put('seller', $seller);
                return redirect('/seller-dashboard')->with('email', $seller->email);
            } else {
                return back()
                    ->with([
                        'alert' => 'Incorrect password',
                    ])
                    ->withInput();
            }
        } else {
            return back()
                ->with([
                    'alert' => 'No account found for this email',
                ])
                ->withInput();
        }
    }

    public function list(Request $request)
    {
        $sellerId = $request->session()->get('seller')->id;
        $selleremail = $request->session()->get('seller')->email;
        $keyword = $request->input('keyword');
        $category = $request->input('category');
        $date = $request->input('date');
        $city = $request->input('city');
        $quantity = $request->input('quantity');
        $product_name = $request->input('product_name');

        $category_data = DB::table('categories')
            ->select('categories.*', 'categories.name as title')
            ->get();
        $recordsPerPage = $request->input('r_page', 15);
        $currentDate = \Carbon\Carbon::now();

        $query = DB::table('qutation_form')
            ->where('qutation_form.email', '!=', $selleremail)

            ->leftJoin('seller', 'qutation_form.email', '=', 'seller.email')
            ->leftJoin('product', 'qutation_form.product_id', '=', 'product.id')
            ->leftJoin('product_brands as pb', 'product.id', '=', 'pb.product_id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->where('qutation_form.email', '!=', $selleremail)
            ->whereRaw("FIND_IN_SET(?, qutation_form.seller_id)", [$sellerId])
            ->whereRaw("DATE_ADD(date_time, INTERVAL bid_time DAY) >= ?", [$currentDate])
            ->select(
                // qutation_form columns
                'qutation_form.id as id',
                'qutation_form.name as name',
                'qutation_form.email as email',
                'qutation_form.product_id as qutation_form_product_id',
                'qutation_form.product_img as qutation_form_product_img',
                'qutation_form.product_name  as qutation_form_product_name',
                'pb.brand_name     as qutation_form_product_brand',
                'qutation_form.message   as qutation_form_message',
                'qutation_form.location  as qutation_form_location',
                'qutation_form.address   as qutation_form_address',
                'qutation_form.zipcode   as qutation_form_zipcode',
                'qutation_form.state     as qutation_form_state',
                'qutation_form.city  as qutation_form_city',
                'qutation_form.bid_area  as qutation_form_bid_area',
                'qutation_form.date_time     as date_time',
                'qutation_form.bid_time  as bid_time',
                'qutation_form.material  as qutation_form_material',
                'qutation_form.image     as qutation_form_image',
                'qutation_form.latitude  as qutation_form_latitude',
                'qutation_form.longitude     as qutation_form_longitude',
                'qutation_form.seller_id     as qutation_form_seller_id',
                'qutation_form.unit  as unit',
                'qutation_form.quantity  as quantity',
                'qutation_form.status    as qutation_form_status',

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
                // sub category columns
                'sc.id as sub_id',
                'sc.name as sub_name',
                'sc.category_id as sub_cat_id',
                'sc.created_at as sub_post_date',
                'sc.image as sub_image',
                'sc.slug as sub_slug',
                'sc.status as sub_status',
                'sc.order_by as sub_order_by',

                // category columns
                'c.id as category_id',
                'c.name as category_name',
                'c.created_at as category_post_date',
                'c.image as category_image',
                'c.slug as category_slug',
                'c.status as category_status'
            );

        if ($category) {
            $query->where('c.id', 'like', '%' . $category . '%');
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
        if ($product_name) {
            $query->where('product.title', 'like', '%' . $product_name . '%');
        }

        $blogs = $query->orderBy('qutation_form.id', 'desc')->paginate($recordsPerPage);

        $data = [
            'keyword' => $keyword,
            'date' => $date,
            'city' => $city,
            'quantity' => $quantity,
            'category' => $category,
            'product_name' => $product_name,
            'r_page' => $recordsPerPage,
        ];

        return view('seller.enquiry.list', compact('blogs', 'data', 'category_data'));
    }

    public function viewwork(Request $request, $id)
    {
        $sellerId = $request->session()->get('seller')->id;
        $keyword = $request->input('keyword');
        $category = $request->input('category');
        $date = $request->input('date');
        $city = $request->input('city');
        $quantity = $request->input('quantity');
        $product_name = $request->input('product_name');

        $category_data = DB::table('categories')
            ->select('categories.*', 'categories.name as title')
            ->get();
        $recordsPerPage = $request->input('r_page', 15);
        $currentDate = \Carbon\Carbon::now();

        $query = DB::table('qutation_form')
            ->join('bidding_price', 'qutation_form.id', '=', 'bidding_price.data_id')
            ->leftJoin('product', 'qutation_form.product_id', '=', 'product.id')
            ->leftJoin('product_brands as pb', 'product.id', '=', 'pb.product_id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->where('bidding_price.seller_email', $id)
            ->where('bidding_price.payment_status', 'success')
            ->where('bidding_price.action', 1)
            ->select('qutation_form.*', 'bidding_price.price as b_price', 'bidding_price.rate as b_rate', 'pb.brand_name as qutation_form_product_brand');

        if ($category) {
            $query->where('c.id', 'like', '%' . $category . '%');
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
        if ($product_name) {
            $query->where('product.title', 'like', '%' . $product_name . '%');
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

        return view('seller.accounting.viewwork', compact('blogs', 'data', 'category_data'));
    }

    public function deactivelist(Request $request)
    {
        $sellerId = $request->session()->get('seller')->id;
        $keyword = $request->input('keyword');
        $category = $request->input('category');
        $date = $request->input('date');
        $city = $request->input('city');
        $quantity = $request->input('quantity');
        $product_name = $request->input('product_name');
        $recordsPerPage = $request->input('r_page', 15);
        $currentDate = \Carbon\Carbon::now();
        $category_data = DB::table('categories')
            ->select('categories.*', 'categories.name as title')
            ->get();

        // $query = DB::table('qutation_form')
        //     ->whereRaw("FIND_IN_SET(?, seller_id)", [$sellerId])
        //     ->whereRaw("DATE_ADD(date_time, INTERVAL bid_time DAY) <= ?", [$currentDate]);

        $query = DB::table('qutation_form')

            ->leftJoin('seller', 'qutation_form.email', '=', 'seller.email')
            ->leftJoin('product', 'qutation_form.product_id', '=', 'product.id')
            ->leftJoin('product_brands as pb', 'product.id', '=', 'pb.product_id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->whereRaw("FIND_IN_SET(?, qutation_form.seller_id)", [$sellerId])
            ->whereRaw("DATE_ADD(date_time, INTERVAL bid_time DAY) <= ?", [$currentDate])
            ->select(
                // qutation_form columns
                'qutation_form.id as id',
                'qutation_form.name as name',
                'qutation_form.email as email',
                'qutation_form.product_id as qutation_form_product_id',
                'qutation_form.product_img as qutation_form_product_img',
                'qutation_form.product_name  as qutation_form_product_name',
                'pb.brand_name     as qutation_form_product_brand',
                'qutation_form.message   as qutation_form_message',
                'qutation_form.location  as qutation_form_location',
                'qutation_form.address   as qutation_form_address',
                'qutation_form.zipcode   as qutation_form_zipcode',
                'qutation_form.state     as qutation_form_state',
                'qutation_form.city  as qutation_form_city',
                'qutation_form.bid_area  as qutation_form_bid_area',
                'qutation_form.date_time     as date_time',
                'qutation_form.bid_time  as bid_time',
                'qutation_form.material  as qutation_form_material',
                'qutation_form.image     as qutation_form_image',
                'qutation_form.latitude  as qutation_form_latitude',
                'qutation_form.longitude     as qutation_form_longitude',
                'qutation_form.seller_id     as qutation_form_seller_id',
                'qutation_form.unit  as unit',
                'qutation_form.quantity  as quantity',
                'qutation_form.status    as qutation_form_status',

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

                // sub category columns
                'sc.id as sub_id',
                'sc.name as sub_name',
                'sc.category_id as sub_cat_id',
                'sc.created_at as sub_post_date',
                'sc.image as sub_image',
                'sc.slug as sub_slug',
                'sc.status as sub_status',
                'sc.order_by as sub_order_by',

                // category columns
                'c.id as category_id',
                'c.name as category_name',
                'c.created_at as category_post_date',
                'c.image as category_image',
                'c.slug as category_slug',
                'c.status as category_status'
            );

        if ($category) {
            $query->where('c.id', 'like', '%' . $category . '%');
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

        return view('seller.enquiry.deactivelist', compact('blogs', 'data', 'category_data'));
    }

    public function myenclist(Request $request)
    {
        $sellerId = $request->session()->get('seller')->id;
        $keyword = $request->input('keyword');
        $category = $request->input('category');
        $date = $request->input('date');
        $city = $request->input('city');
        $quantity = $request->input('quantity');
        $qutationId = $request->input('qutation_id');
        $product_name = $request->input('product_name');
        $recordsPerPage = $request->input('r_page', 15);
        $currentDate = \Carbon\Carbon::now();
        $category_data = DB::table('categories')
            ->select('categories.*', 'categories.name as title')
            ->get();
        $selleremail = $request->session()->get('seller')->email;

        $query = DB::table('qutation_form')

            ->leftJoin('seller', 'qutation_form.email', '=', 'seller.email')
            ->leftJoin('product', 'qutation_form.product_id', '=', 'product.id')
            ->leftJoin('product_brands as pb', 'product.id', '=', 'pb.product_id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->where('qutation_form.email', $selleremail)
            ->select(
                'qutation_form.id as id',
                'qutation_form.id as qutation_id',
                'qutation_form.name as name',
                'qutation_form.email as email',
                'qutation_form.product_id as qutation_form_product_id',
                'qutation_form.product_img as qutation_form_product_img',
                'qutation_form.product_name  as qutation_form_product_name',
                'pb.brand_name     as qutation_form_product_brand',
                'qutation_form.message   as qutation_form_message',
                'qutation_form.location  as qutation_form_location',
                'qutation_form.address   as qutation_form_address',
                'qutation_form.zipcode   as qutation_form_zipcode',
                'qutation_form.state     as qutation_form_state',
                'qutation_form.city  as qutation_form_city',
                'qutation_form.bid_area  as qutation_form_bid_area',
                'qutation_form.date_time     as date_time',
                'qutation_form.bid_time  as bid_time',
                'qutation_form.material  as qutation_form_material',
                'qutation_form.image     as qutation_form_image',
                'qutation_form.latitude  as qutation_form_latitude',
                'qutation_form.longitude     as qutation_form_longitude',
                'qutation_form.seller_id     as qutation_form_seller_id',
                'qutation_form.unit  as unit',
                'qutation_form.quantity  as quantity',
                'qutation_form.status    as qutation_form_status',

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

                // sub category columns
                'sc.id as sub_id',
                'sc.name as sub_name',
                'sc.category_id as sub_cat_id',
                'sc.created_at as sub_post_date',
                'sc.image as sub_image',
                'sc.slug as sub_slug',
                'sc.status as sub_status',
                'sc.order_by as sub_order_by',

                // category columns
                'c.id as category_id',
                'c.name as category_name',
                'c.created_at as category_post_date',
                'c.image as category_image',
                'c.slug as category_slug',
                'c.status as category_status'
            );

        if ($category) {
            $query->where('c.id', 'like', '%' . $category . '%');
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
        if ($qutationId) {
            $query->where('qutation_form.id', 'like', '%' . $qutationId . '%');
        }
        if ($product_name) {
            $query->where('product.title', 'like', '%' . $product_name . '%');
        }

        $blogs = $query->orderBy('qutation_form.id', 'desc')->paginate($recordsPerPage);
        $data = [
            'keyword' => $keyword,
            'date' => $date,
            'city' => $city,
            'quantity' => $quantity,
            'category' => $category,
            'product_name' => $product_name,
            'r_page' => $recordsPerPage,
            'qutation_id' => $qutationId,
        ];

        return view('seller.enquiry.myenclist', compact('blogs', 'data', 'category_data'));
    }

    public function update_lat_long(Request $request)
    {
        $email = $request->email;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $updateData = [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];

        $updated = DB::table('seller')
            ->where('email', $email)
            ->update($updateData);
        if ($updated) {
            return redirect('/seller-dashboard')->with('success', 'updated successfully.');
        } else {
            return back();
        }
    }

    public function bidding_price(Request $request)
    {
        $validator = $request->validate([
            'price' => 'required',
        ]);

        $price = $request->price;
        $rate = $request->rate;
        $data_id = $request->data_id;
        $product_quantity = $request->product_quantity;
        $user_email = $request->user_email;
        $product_name = $request->product_name;
        $product_id = $request->product_id;
        $seller_email = $request->seller_email;
        $filename = $request->filename;

        $amount = $price;
        // $amount = $price * $product_quantity;

        $existingRecord = DB::table('bidding_price')
            ->where('data_id', $request->data_id)
            ->where('seller_email', $request->seller_email)
            ->where('payment_status', 'success')
            ->exists();

        if ($existingRecord) {
            return response()->make("<script>alert('You have already paid for this.'); window.location.href = '" . url('seller/enquiry/list')->previous() . "';</script>");
        } else {
            $merchantId = 'M22I5XAFXIGHT';
            $apiKey = '096dcc76-f0d9-41f3-93c8-f54c7b506668';
            $redirectUrl = url('confirm');
            $order_id = uniqid();

            $transaction_data = [
                'merchantId' => "$merchantId",
                'merchantTransactionId' => "$order_id",
                "merchantUserId" => $order_id,
                'amount' => $amount * 100,
                'redirectUrl' => "$redirectUrl",
                'redirectMode' => "POST",
                'callbackUrl' => "$redirectUrl",
                "paymentInstrument" => [
                    "type" => "PAY_PAGE",
                ],
            ];

            $encode = json_encode($transaction_data);
            $payloadMain = base64_encode($encode);
            $salt_index = 1;
            $payload = $payloadMain . "/pg/v1/pay" . $apiKey;
            $sha256 = hash("sha256", $payload);
            $final_x_header = $sha256 . '###' . $salt_index;
            $request = json_encode(['request' => $payloadMain]);

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.phonepe.com/apis/hermes/pg/v1/pay",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $request,
                CURLOPT_HTTPHEADER => ["Content-Type: application/json", "X-VERIFY: " . $final_x_header, "accept: application/json"],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $res = json_decode($response);

                if (isset($res->code) && $res->code == 'PAYMENT_INITIATED') {
                    $payUrl = $res->data->instrumentResponse->redirectInfo->url;

                    // Insert the bidding record after successful payment initiation
                    DB::table('bidding_price')->insert([
                        'price' => $amount,
                        'data_id' => $data_id,
                        'user_email' => $user_email,
                        'rate' => $rate,
                        'product_id' => $product_id,
                        'product_name' => $product_name,
                        'seller_email' => $seller_email,
                        'filename' => $filename,
                        'payment_status' => 'pending',
                        'transaction_id' => $order_id,
                    ]);

                    return redirect()->away($payUrl);
                } else {
                    // Handle any error response from PhonePe
                    dd('ERROR : ' . $res);
                }
            }
        }

        return back()->with('error', 'Something went wrong.');
    }

    public function confirmPayment(Request $request)
    {
        if ($request->code == 'PAYMENT_SUCCESS') {
            $transactionId = $request->transactionId;
            $merchantId = $request->merchantId;
            $providerReferenceId = $request->providerReferenceId;
            $merchantOrderId = $request->merchantOrderId;
            $checksum = $request->checksum;
            $status = $request->code;

            //Transaction completed, You can add transaction details into database

            $data = [
                'providerReferenceId' => $providerReferenceId,
                'checksum' => $checksum,
            ];
            if ($merchantOrderId != '') {
                $data['merchantOrderId'] = $merchantOrderId;
            }

            // dd($request);

            DB::table('bidding_price')
                ->where('transaction_id', $transactionId)
                ->update(['payment_status' => 'success']);
            $query = DB::table('bidding_price')
                ->where('transaction_id', $transactionId)
                ->first();
            $sellemail = $query->seller_email;

            $query1 = DB::table('seller')
                ->where('email', $sellemail)
                ->where('verify', '1')
                ->first();
            $request->session()->put('seller', $query1);

            //  MSG91 Transactional SMS
            if ($query1 && $query1->phone) {
                $phone = $query1->phone;
                $authkey = "446194AThF7RkYkZ687de9a2P1"; //  Replace with your actual MSG91 auth key

                $smsData = [
                    "template_id" => "687e4a11d6fc0548a34e1162", //  Your template ID
                    "short_url" => 0,
                    "recipients" => [
                        [
                            "mobiles" => "91" . $phone,
                            "name" => $query1->name,
                            "amount" => (string) $query->price ?? '0',
                        ],
                    ],
                ];

                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => "https://control.msg91.com/api/v5/flow/",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => json_encode($smsData),
                    CURLOPT_HTTPHEADER => ["authkey: $authkey", "Content-Type: application/json"],
                ]);

                $response = curl_exec($curl);
                curl_close($curl);
            }

            return redirect('seller/enquiry/list')->with('success', 'Successfully  completed payment');
    
        } else {
            $statusCode = $request->code ?? 'UNKNOWN_ERROR';
            $transactionId = $request->transactionId ?? null;

            if ($transactionId) {
                DB::table('bidding_price')
                    ->where('transaction_id', $transactionId)
                    ->update(['payment_status' => 'cancelled']);
            }

            $message = 'An unexpected error occurred while confirming the payment. Please try again later.';
            if ($statusCode === 'PAYMENT_CANCELLED') {
                $message = 'Payment was cancelled. Please try again later.';
            } elseif ($statusCode && $statusCode !== 'UNKNOWN_ERROR') {
                $message = 'Payment failed with status: ' . $statusCode . '. Please try again later.';
            }

            Log::warning('Payment confirmation failed', [
                'status_code' => $statusCode,
                'transaction_id' => $transactionId,
                'merchant_id' => $request->merchantId ?? null,
                'provider_reference_id' => $request->providerReferenceId ?? null,
            ]);

            return redirect('seller/enquiry/list')->with('error', $message);
        }
    }

    public function vewsell(Request $request, $id)
    {
        $sellerEmail = $request->session()->get('seller')->email;

        $query = DB::table('qutation_form')
            ->leftJoin('seller', 'qutation_form.email', '=', 'seller.email')
            ->leftJoin('product', 'qutation_form.product_id', '=', 'product.id')
            ->leftJoin('product_brands as pb', 'product.id', '=', 'pb.product_id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->where('qutation_form.id', $id)
            ->select(
                // qutation_form columns
                'qutation_form.id as qutation_form_id',
                'qutation_form.name as qutation_form_name',
                'qutation_form.email as qutation_form_email',
                'qutation_form.product_id as qutation_form_product_id',
                'qutation_form.product_img as qutation_form_product_img',
                'qutation_form.product_name  as qutation_form_product_name',
                'pb.brand_name     as qutation_form_product_brand',
                'qutation_form.message   as qutation_form_message',
                'qutation_form.location  as qutation_form_location',
                'qutation_form.address   as qutation_form_address',
                'qutation_form.zipcode   as qutation_form_zipcode',
                'qutation_form.state     as qutation_form_state',
                'qutation_form.city  as qutation_form_city',
                'qutation_form.bid_area  as qutation_form_bid_area',
                'qutation_form.bid_time  as qutation_form_bid_time',
                'qutation_form.material  as qutation_form_material',
                'qutation_form.image     as qutation_form_image',
                'qutation_form.latitude  as qutation_form_latitude',
                'qutation_form.longitude     as qutation_form_longitude',
                'qutation_form.seller_id     as qutation_form_seller_id',
                'qutation_form.unit  as qutation_form_date_unit',
                'qutation_form.quantity  as qutation_form_quantity',
                'qutation_form.status    as qutation_form_status',

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

                // sub category columns
                'sc.id as sub_id',
                'sc.name as sub_name',
                'sc.category_id as sub_cat_id',
                'sc.created_at as sub_post_date',
                'sc.image as sub_image',
                'sc.slug as sub_slug',
                'sc.status as sub_status',
                'sc.order_by as sub_order_by',

                // category columns
                'c.id as category_id',
                'c.name as category_name',
                'c.created_at as category_post_date',
                'c.image as category_image',
                'c.slug as category_slug',
                'c.status as category_status'
            )
            ->first();

        return view('/seller/enquiry/view', compact('query'));
    }

    public function vewfile($id)
    {
        $query = DB::table('qutation_form')
            ->where('id', $id)
            ->first();
        $images = explode(',', $query->image);
        return view('/seller/enquiry/file', compact('query', 'images'));
    }

    public function accountinglist(Request $request)
    {
        $selleremail = $request->session()->get('seller')->email;

        $keyword = $request->input('keyword');
        $category = $request->input('category');
        $date = $request->input('date');
        $city = $request->input('city');
        $quantity = $request->input('quantity');
        $product_name = $request->input('product_name');

        $category_data = DB::table('categories')
            ->select('categories.*', 'categories.name as title')
            ->get();
        $recordsPerPage = $request->input('r_page', 25);


        $query = DB::table('bidding_price')

            ->leftJoin('seller', 'bidding_price.user_email', '=', 'seller.email')
            ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')
            ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
            ->leftJoin('product_brands as pb', 'product.id', '=', 'pb.product_id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->where('bidding_price.seller_email', $selleremail)
            ->where('payment_status', 'success')

            ->select(
                // bidding_price columns
                'bidding_price.id as id',
                'bidding_price.price as price',
                'bidding_price.action as action',
                'bidding_price.data_id as data_id',
                'bidding_price.rate as rate',

                'bidding_price.seller_email as seller_email',
                'bidding_price.product_id as bidding_price_product_id',

                'bidding_price.product_name  as bidding_price_product_name',

                //qf
                'qutation_form.bid_time as bid_time',
                'qutation_form.material as material',
                'qutation_form.id as id',
                'qutation_form.name as name',
                'qutation_form.email as email',
                'qutation_form.product_id as qutation_form_product_id',
                'qutation_form.product_img as qutation_form_product_img',
                // 'qutation_form.product_name  as qutation_form_product_name',
                'pb.brand_name as qutation_form_product_brand',
                'qutation_form.message as qutation_form_message',
                // 'qutation_form.location  as qutation_form_location',
                'qutation_form.address as qutation_form_address',
                'qutation_form.zipcode as qutation_form_zipcode',
                'qutation_form.state as qutation_form_state',
                'qutation_form.city as qutation_form_city',
                'qutation_form.date_time as date_time',
                // 'qutation_form.bid_time  as bid_time',
                'qutation_form.image as qutation_form_image',
                'qutation_form.latitude as qutation_form_latitude',
                'qutation_form.longitude as qutation_form_longitude',
                'qutation_form.seller_id as qutation_form_seller_id',
                'qutation_form.unit  as unit',
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
                // sub category columns
                'sc.id as sub_id',
                'sc.name as sub_name',
                'sc.category_id as sub_cat_id',
                'sc.created_at as sub_post_date',
                'sc.image as sub_image',
                'sc.slug as sub_slug',
                'sc.status as sub_status',
                'sc.order_by as sub_order_by',

                // category columns
                'c.id as category_id',
                'c.name as category_name',
                'c.created_at as category_post_date',
                'c.image as category_image',
                'c.slug as category_slug',
                'c.status as category_status'
            );

        if ($category) {
            $query->where('c.id', 'like', '%' . $category . '%');
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

        //    $blogs = $query->orderBy('qutation_form.id', 'desc')->paginate($recordsPerPage);
        $blogs = $query->orderBy('bidding_price.id', 'desc')->paginate($recordsPerPage);

        $datas = [
            'keyword' => $keyword,
            'date' => $date,
            'city' => $city,
            'quantity' => $quantity,
            'category' => $category,
            'product_name' => $product_name,
            'r_page' => $recordsPerPage,
        ];

        return view('seller.accounting.list', compact('blogs', 'datas', 'category_data'));
    }

    public function accbid(Request $request)
    {
        $selleremail = $request->session()->get('seller')->email;

        $keyword = $request->input('keyword');
        $category = $request->input('category');
        $date = $request->input('date');
        $city = $request->input('city');
        $quantity = $request->input('quantity');
        $product_name = $request->input('product_name');

        $category_data = DB::table('categories')
            ->select('categories.*', 'categories.name as title')
            ->get();
        $recordsPerPage = $request->input('r_page', 25);

        $query = DB::table('bidding_price')

            ->leftJoin('seller', 'bidding_price.user_email', '=', 'seller.email')
            ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')
            ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
            ->leftJoin('product_brands as pb', 'product.id', '=', 'pb.product_id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->where('bidding_price.seller_email', $selleremail)
            ->where('payment_status', 'success')
            ->where('action', '1')
            // ->where('hide', '0')
            ->select(
                // bidding_price columns
                'bidding_price.id as id',
                'bidding_price.price as price',
                'bidding_price.action as action',
                'bidding_price.data_id as data_id',
                'bidding_price.rate as rate',

                'bidding_price.seller_email as seller_email',
                'bidding_price.product_id as bidding_price_product_id',

                'bidding_price.product_name  as bidding_price_product_name',

                //qf
                'qutation_form.bid_time as bid_time',
                'qutation_form.material as material',
                'qutation_form.id as id',
                'qutation_form.name as name',
                'qutation_form.email as email',
                'qutation_form.product_id as qutation_form_product_id',
                'qutation_form.product_img as qutation_form_product_img',
                // 'qutation_form.product_name  as qutation_form_product_name',
                'pb.brand_name as qutation_form_product_brand',
                'qutation_form.message as qutation_form_message',
                // 'qutation_form.location  as qutation_form_location',
                'qutation_form.address as qutation_form_address',
                'qutation_form.zipcode as qutation_form_zipcode',
                'qutation_form.state as qutation_form_state',
                'qutation_form.city as qutation_form_city',
                'qutation_form.date_time as date_time',
                // 'qutation_form.bid_time  as bid_time',
                'qutation_form.image as qutation_form_image',
                'qutation_form.latitude as qutation_form_latitude',
                'qutation_form.longitude as qutation_form_longitude',
                'qutation_form.seller_id as qutation_form_seller_id',
                'qutation_form.unit  as unit',
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
                // sub category columns
                'sc.id as sub_id',
                'sc.name as sub_name',
                'sc.category_id as sub_cat_id',
                'sc.created_at as sub_post_date',
                'sc.image as sub_image',
                'sc.slug as sub_slug',
                'sc.status as sub_status',
                'sc.order_by as sub_order_by',

                // category columns
                'c.id as category_id',
                'c.name as category_name',
                'c.created_at as category_post_date',
                'c.image as category_image',
                'c.slug as category_slug',
                'c.status as category_status'
            );

        if ($category) {
            $query->where('c.id', 'like', '%' . $category . '%');
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

        //    $blogs = $query->orderBy('qutation_form.id', 'desc')->paginate($recordsPerPage);
        $blogs = $query->orderBy('bidding_price.id', 'desc')->paginate($recordsPerPage);

        $datas = [
            'keyword' => $keyword,
            'date' => $date,
            'city' => $city,
            'quantity' => $quantity,
            'category' => $category,
            'product_name' => $product_name,
            'r_page' => $recordsPerPage,
        ];

        return view('seller.accounting.accbid', compact('blogs', 'datas', 'category_data'));
    }

    public function biddrecive(Request $request)
    {
        $selleremail = $request->session()->get('seller')->email;

        $keyword = $request->input('keyword');
        $category = $request->input('category');
        $date = $request->input('date');
        $city = $request->input('city');
        $quantity = $request->input('quantity');
        $product_name = $request->input('product_name');

        $category_data = DB::table('categories')
            ->select('categories.*', 'categories.name as title')
            ->get();
        $recordsPerPage = $request->input('r_page', 25);

        $query = DB::table('bidding_price')
            ->leftJoin('seller', 'bidding_price.seller_email', '=', 'seller.email')
            ->leftJoin('product', 'bidding_price.product_id', '=', 'product.id')
            ->leftJoin('qutation_form', 'bidding_price.data_id', '=', 'qutation_form.id')
            ->leftJoin('product_brands as pb', 'product.id', '=', 'pb.product_id')
            ->leftJoin('sub_categories as sc', 'product.sub_id', '=', 'sc.id')
            ->leftJoin('categories as c', 'sc.category_id', '=', 'c.id')
            ->where('bidding_price.user_email', $selleremail)
            ->where('payment_status', 'success')
            ->orderByDesc('bidding_price.id')
            ->select(
                // bidding_price columns
                'bidding_price.id as id',
                'bidding_price.price as price',
                'bidding_price.action as action',
                'bidding_price.data_id as data_id',
                'bidding_price.rate as rate',

                'bidding_price.seller_email as seller_email',
                'bidding_price.product_id as bidding_price_product_id',
                'bidding_price.product_name  as bidding_price_product_name',
                //qf
                'qutation_form.bid_time as bid_time',
                'qutation_form.material as material',
                'qutation_form.id as id',
                'qutation_form.name as name',
                'qutation_form.email as email',
                'qutation_form.product_id as qutation_form_product_id',
                'qutation_form.product_img as qutation_form_product_img',
                // 'qutation_form.product_name  as qutation_form_product_name',
                'pb.brand_name as qutation_form_product_brand',
                'qutation_form.message as qutation_form_message',
                // 'qutation_form.location  as qutation_form_location',
                'qutation_form.address as qutation_form_address',
                'qutation_form.zipcode as qutation_form_zipcode',
                'qutation_form.state as qutation_form_state',
                'qutation_form.city as qutation_form_city',
                'qutation_form.date_time as date_time',
                // 'qutation_form.bid_time  as bid_time',
                'qutation_form.image as qutation_form_image',
                'qutation_form.latitude as qutation_form_latitude',
                'qutation_form.longitude as qutation_form_longitude',
                'qutation_form.seller_id as qutation_form_seller_id',
                'qutation_form.unit  as unit',
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
                // sub category columns
                'sc.id as sub_id',
                'sc.name as sub_name',
                'sc.category_id as sub_cat_id',
                'sc.created_at as sub_post_date',
                'sc.image as sub_image',
                'sc.slug as sub_slug',
                'sc.status as sub_status',
                'sc.order_by as sub_order_by',

                // category columns
                'c.id as category_id',
                'c.name as category_name',
                'c.created_at as category_post_date',
                'c.image as category_image',
                'c.slug as category_slug',
                'c.status as category_status'
            );

        if ($category) {
            $query->where('c.id', 'like', '%' . $category . '%');
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

        $blogs = $query->orderBy('qutation_form.id', 'desc')->paginate($recordsPerPage);

        $datas = [
            'keyword' => $keyword,
            'date' => $date,
            'city' => $city,
            'quantity' => $quantity,
            'category' => $category,
            'product_name' => $product_name,
            'r_page' => $recordsPerPage,
        ];

        return view('seller.accounting.biddrecive', compact('blogs', 'datas', 'category_data'));
    }

    public function accepet($id, $data_id)
    {
        $inserted = DB::table('bidding_price')
            ->where('id', $id)
            ->update(['action' => 1]);
        $inserted = DB::table('bidding_price')
            ->where('data_id', $data_id)
            ->update(['hide' => 1]);
        if ($inserted) {
            return back()->with('success', 'Status Has Been Changed.');
        } else {
            return back()->with('error', 'Updation Failed.');
        }
    }

    public function totalshare(Request $request)
    {
        $keyword = $request->input('keyword');
        $recordsPerPage = $request->input('r_page', 15);

        $query = DB::table('seller')
            ->where('verify', 1)
            ->where('ref_by', session('seller')->ref_code);

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')->orWhere('email', 'like', '%' . $keyword . '%');
            });
        }

        $blogs = $query->orderBy('id', 'desc')->paginate($recordsPerPage);
        $data = [
            'keyword' => $keyword,
            'r_page' => $recordsPerPage,
        ];
        return view('seller.accounting.totalshare', compact('blogs', 'data'));
    }

    public function lock_location($id)
    {
        $inserted = DB::table('seller')
            ->where('id', $id)
            ->update(['lock_location' => 0]);
        if ($inserted) {
            return back()->with('success', 'Status Has Been Changed.');
        } else {
            return back()->with('error', 'Updation Failed.');
        }
    }

    public function unlock_location($id)
    {
        $inserted = DB::table('seller')
            ->where('id', $id)
            ->update(['lock_location' => 1]);
        if ($inserted) {
            return back()->with('success', 'Status Has Been Changed.');
        } else {
            return back()->with('error', 'Updation Failed.');
        }
    }

}
