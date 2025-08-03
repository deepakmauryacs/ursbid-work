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

class SellerloginController extends Controller
{
    public function seller_register()
    {
      return view('frontend/seller-register');
    
    }

    public function update_account($id){
        $blog = DB::table('seller')->where('hash_id', $id)->first();
        return view('/seller/delete/update', compact('blog'));
      }


      public function update_details(Request $request, $id)
      {
          $validator = $request->validate([
              'name' => 'required',
              'gst' => 'required',
              'phone' => 'required',
              
          ]);
        
       
          
          $updateData = [
              'name' => $request->name,
              'gst' => $request->gst,
              'phone' => $request->phone,
             
          ];
          
          $updated = DB::table('seller')->where('id', $id)->update($updateData);
          if ($updated) {
            return back()->with('success', 'updated successfully.');
          } else {
              return back()->withErrors(['error' => 'Update failed.'])->withInput();
          }
      }


    public function delete_account(Request $request)
    {
            $otp = strval(rand(100000, 999999));

           $request->session()->put('seller_acc_delete_otp', $otp);
         
            $selleremail = $request->session()->get('seller')->email;
            
            Mail::send([], [], function ($message) use ($otp, $selleremail) {
                $message->to($selleremail)
                    ->subject('Confirm Otp')
                    ->html("Confirm Your otp to delete your account.<br>OTP: $otp");
                    
            });

            return view('seller/delete/add');
    }

    public function delete_acc(Request $request)
{
    $otp = $request->otp;
    $selleremail = $request->session()->get('seller')->email;

    $sellerotp = $request->session()->get('seller_acc_delete_otp');

    if ($sellerotp == $otp) {
        DB::table('seller')->where('email', $selleremail)->delete();
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
    // public function seller_create(Request $request)
    // {

    //     $validator = $request->validate([
    //         'email'=>'required|unique:seller',
    //         'name'=>'required',
    //         'phone'=>'required',
    //         'password'=>'required',
    //         // 'gender'=>'required',
            
    //     ]);
       
    //      $seller = DB::table('seller')->insert([
    //         'email' => $request->email, 
    //         'name' => $request->name, 
    //         'phone' => $request->phone, 
    //         'password' => $request->password, 
    //         'pancard' => $request->pancard, 
    //         'gst' => $request->gst, 
    //         'user_type' => $request->user_type, 
    //         // 'gender' => $request->gender,
            
            
    //     ]);
    
    
    //     if($seller)
    //            {
    //             return back()->with('success', 'Account Has Been Created successfully. Now login Please');
    //            }else{
    //             return back()->withErrors(['error' => 'Insertion Failed.'])->withInput();
    //            }
    // //   return view('frontend/seller-register');
    // }



    public function seller_create(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'password' => 'required',
        ]);
    
        if ($request->referral_code) {
            $existcode = DB::table('seller')
                ->where('ref_code', $request->referral_code)
                ->first();    
    
            if (!$existcode) {
                return back()->withErrors(['ref_error' => 'Sorry, Referral Code is wrong.'])->withInput();
            }
        }
    
        $existingSeller = DB::table('seller')
            ->where('email', $request->email)
            ->first();
    
        if ($existingSeller) {
            if ($existingSeller->verify == 1) {
                return back()->withErrors(['email' => 'Sorry, Email already exists.'])->withInput();
            } else {
                // Email exists but is not verified, resend the OTP email
                $otp = strval(rand(100000, 999999));
                $hashId = Str::random(32);
    
                // Update the existing record with new OTP and hash_id
                DB::table('seller')->where('id', $existingSeller->id)->update([
                    'otp' => $otp,
                    'hash_id' => $hashId
                ]);
    
                $this->sendOtpEmail($request->email, $otp, $hashId);
                return redirect()->route('verify.otp', ['hash_id' => $hashId]);
            }
        }
    
        $ref = Str::random(5);
        $random_numbers = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
        $ref_code = strtoupper($ref . $random_numbers);
    
        $otp = strval(rand(100000, 999999));
        $acc_type = implode(',', $request->acc_type);
        $pro_ser = implode(',', $request->pro_ser);
    
        $sellerId = DB::table('seller')->insertGetId([
            'email' => $request->email,
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => $request->password,
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
    
            DB::table('seller')->where('id', $sellerId)->update(['hash_id' => $hashId]);
    
            $this->sendOtpEmail($request->email, $otp, $hashId);
            return redirect()->route('verify.otp', ['hash_id' => $hashId]);
        } else {
            return back()->withErrors(['error' => 'Insertion Failed.'])->withInput();
        }
    }
    

    private function sendOtpEmail($email, $otp, $hashId)
    {
        Mail::to($email)->send(new OtpVerificationMail($otp, $hashId));
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
            DB::table('seller')->where('hash_id', $request->hash_id)->update(['otp' => null, 'verify' => '1']);
            return redirect('/seller-dashboard')->with('email', $seller->email);
        } else {
            return back()->withErrors(['error' => 'Invalid OTP Please try again'])->withInput();
        }
    }


    public function authenticate2(Request $request)
    {
      $validator = $request->validate([
        'email' => 'required',
        'password' => 'required'
    ]);

    $buyer = Seller::where('email', $request->email)->first();
    if ($buyer) {
        if ($request->password == $buyer->password) {
            $request->session()->put('seller', $buyer);
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








    public function seller_login()
    {

      return view('frontend/seller-login');
    }

    public function authenticate(Request $request)
    {
      $validator = $request->validate([
        'email' => 'required',
        'password' => 'required'
    ]);

    $seller = Seller::where('email', $request->email)->where('verify',1)->where('status',1)->first();
    if ($seller) {
        if ($request->password == $seller->password && $seller->verify==1) {
            $email= $request->email;
          
            $request->session()->put('seller', $seller);
            return redirect('/seller-dashboard')->with('email', $seller->email);
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
    //         return redirect('/')->withErrors($validator)->withInput();
    //     }
    // } else {
    //     $request->session()->flash('fail', 'No account found for this email');
    //     return redirect('/')->withErrors($validator)->withInput();
      
    // }
    }

    public function list(Request $request)
    {
        $sellerId = $request->session()->get('seller')->id;
        $keyword = $request->input('keyword');
        $recordsPerPage = $request->input('r_page', 15);
        $currentDate = \Carbon\Carbon::now();
    
        $query = DB::table('qutation_form')
            ->whereRaw("FIND_IN_SET(?, seller_id)", [$sellerId])
            ->whereRaw("DATE_ADD(date_time, INTERVAL bid_time DAY) >= ?", [$currentDate]);
    
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('email', 'like', '%' . $keyword . '%')
                  ->orWhere('product_name', 'like', '%' . $keyword . '%')
                  ->orWhere('location', 'like', '%' . $keyword . '%');
            });
        }
    
        $blogs = $query->paginate($recordsPerPage);
        $data = [
            'keyword' => $keyword,
            'r_page' => $recordsPerPage,
        ];
    
        return view('seller.enquiry.list', compact('blogs', 'data'));
    }




    public function deactivelist(Request $request)
    {
        $sellerId = $request->session()->get('seller')->id;
        $keyword = $request->input('keyword');
        $recordsPerPage = $request->input('r_page', 15);
        $currentDate = \Carbon\Carbon::now();
    
        $query = DB::table('qutation_form')
            ->whereRaw("FIND_IN_SET(?, seller_id)", [$sellerId])
            ->whereRaw("DATE_ADD(date_time, INTERVAL bid_time DAY) <= ?", [$currentDate]);
    
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('email', 'like', '%' . $keyword . '%')
                  ->orWhere('product_name', 'like', '%' . $keyword . '%')
                  ->orWhere('location', 'like', '%' . $keyword . '%');
            });
        }
    
        $blogs = $query->paginate($recordsPerPage);
        $data = [
            'keyword' => $keyword,
            'r_page' => $recordsPerPage,
        ];
    
        return view('seller.enquiry.deactivelist', compact('blogs', 'data'));
    }













    

    public function update_lat_long(Request $request)
    {
       
        $email= $request->email;
        $latitude= $request->latitude;
        $longitude= $request->longitude;
        $updateData = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            
        ];
        
        $updated = DB::table('seller')->where('email', $email)->update($updateData);
        if ($updated) {
          return redirect('/seller-dashboard')->with('success', 'updated successfully.');
        } else {
            return back();
        }
    }



    public function bidding_price(Request $request)
{
    $validator = $request->validate([
        'price'=>'required',
    ]);

    $amount = $request->price;
        dd($amount);

    $data_id = $request->data_id;
    $user_email = $request->user_email;
    $product_name = $request->product_name;
    $product_id = $request->product_id;
    $seller_email = $request->seller_email;

    $existingRecord = DB::table('bidding_price')
        ->where('data_id', $request->data_id)
        ->where('seller_email', $request->seller_email)
        ->exists();

    if ($existingRecord) {
        return response()->make("<script>alert('You have already paid for this.'); window.location.href = '".url()->previous()."';</script>");
    } else {
        $merchantId = 'M22I5XAFXIGHT';
        $apiKey = '096dcc76-f0d9-41f3-93c8-f54c7b506668';
        
        $redirectUrl = url('confirm');
        $order_id = uniqid(); 

        $transaction_data = array(
            'merchantId' => "$merchantId",
            'merchantTransactionId' => "$order_id",
            "merchantUserId" => $order_id,
            'amount' => $amount * 100,
            'redirectUrl' => "$redirectUrl",
            'redirectMode' => "POST",
            'callbackUrl' => "$redirectUrl",
            "paymentInstrument" => array(
                "type" => "PAY_PAGE",
            )
        );

        $encode = json_encode($transaction_data);
        $payloadMain = base64_encode($encode);
        $salt_index = 1; 
        $payload = $payloadMain . "/pg/v1/pay" . $apiKey;
        $sha256 = hash("sha256", $payload);
        $final_x_header = $sha256 . '###' . $salt_index;
        $request = json_encode(array('request' => $payloadMain));

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.phonepe.com/apis/hermes/pg/v1/pay",
            // CURLOPT_URL => "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "X-VERIFY: " . $final_x_header,
                "accept: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $res = json_decode($response);

            if (isset($res->code) && ($res->code == 'PAYMENT_INITIATED')) {
                $payUrl = $res->data->instrumentResponse->redirectInfo->url;

                // Insert the bidding record after successful payment initiation
                DB::table('bidding_price')->insert([
                    'price' => $amount,
                    'data_id' => $data_id,
                    'user_email' => $user_email,
                    'product_id' => $product_id,
                    'product_name' => $product_name,
                    'seller_email' => $seller_email,
                    'payment_status' => 'pending',
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































//     public function bidding_price(Request $request)
//     {
//       $validator = $request->validate([
//         'price'=>'required',
        
//     ]);

    
//     $amount = $request->price;
//     $existingRecord = DB::table('bidding_price')
//     ->where('data_id', $request->data_id)
//     ->where('seller_email', $request->seller_email)
//     ->exists();

// // if ($existingRecord) {
//     // $data = DB::table('bidding_price')->where('data_id', $request->data_id)->where('seller_email', $request->seller_email)->update(['price' => $request->price]);
    
// // }

// if ($existingRecord) {
//     return response()->make("<script>alert('You have already paid for this.'); window.location.href = '".url()->previous()."';</script>");
// }

//     else{



            
//         $merchantId = 'PGTESTPAYUAT132';

//         $apiKey = '58f62bdc-2b1f-44a1-9da5-1820a35835f3';
//         $redirectUrl = url('confirm');
//         $order_id = uniqid(); 
        
         
//         $transaction_data = array(
//             'merchantId' => "$merchantId",
//             'merchantTransactionId' => "$order_id",
//             "merchantUserId"=>$order_id,
//             'amount' => $amount*100,
//             'redirectUrl'=>"$redirectUrl",
//             'redirectMode'=>"POST",
//             'callbackUrl'=>"$redirectUrl",
//            "paymentInstrument"=> array(    
//             "type"=> "PAY_PAGE",
//           )
//         );
        
        
//                         $encode = json_encode($transaction_data);
//                         $payloadMain = base64_encode($encode);
//                         $salt_index = 1; //key index 1
//                         $payload = $payloadMain . "/pg/v1/pay" . $apiKey;
//                         $sha256 = hash("sha256", $payload);
//                         $final_x_header = $sha256 . '###' . $salt_index;
//                         $request = json_encode(array('request'=>$payloadMain));
                        
//                         $curl = curl_init();
        
//         curl_setopt_array($curl, [
//           CURLOPT_URL => "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay",
//           CURLOPT_RETURNTRANSFER => true,
//           CURLOPT_ENCODING => "",
//           CURLOPT_MAXREDIRS => 10,
//           CURLOPT_TIMEOUT => 30,
//           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//           CURLOPT_CUSTOMREQUEST => "POST",
//            CURLOPT_POSTFIELDS => $request,
//           CURLOPT_HTTPHEADER => [
//             "Content-Type: application/json",
//              "X-VERIFY: " . $final_x_header,
//              "accept: application/json"
//           ],
//         ]);
        
//         $response = curl_exec($curl);
//         $err = curl_error($curl);
        
//         curl_close($curl);
        
//         if ($err) {
//           echo "cURL Error #:" . $err;
//         } else {
//            $res = json_decode($response);
        
//            // Store information into database
        
//            $data = [
//             'amount' => $amount,
//             'transaction_id' => $order_id,
//             'payment_status' => 'PAYMENT_PENDING',
//             'response_msg'=>$response,
//             'providerReferenceId'=>'',
//             'merchantOrderId'=>'',
//             'checksum'=>''
//         ];
        


      
//         // Payment::create($data);
        
//         // end database insert
           
//            if(isset($res->code) && ($res->code=='PAYMENT_INITIATED')){
         
//           $payUrl=$res->data->instrumentResponse->redirectInfo->url;
         
//          return redirect()->away($payUrl);
//            }else{
//            //HANDLE YOUR ERROR MESSAGE HERE
//                     dd('ERROR : ' . $res);
//            }
//         }
//                 } 

//                 $data_ins = DB::table('bidding_price')->insert([
//                     'price' => $request->price, 
//                     'data_id' => $request->data_id, 
//                     'user_email' => $request->user_email, 
//                     'product_id' => $request->product_id, 
//                     'product_name' => $request->product_name, 
//                     'seller_email' => $request->seller_email, 
//                 ]);

//     if($data_ins)
//            {
//             return back()->with('success', 'Successfully Submited.');
//            }else{
//             return back()->withErrors(['error' => 'Insertion Failed.'])->withInput();
//            }
//     }
   


    public function vewsell($id){
    
        $query = DB::table('qutation_form')->where('id' , $id)->first();
        return view('/seller/enquiry/view', compact('query'));
    }
    public function vewfile($id){
        $query = DB::table('qutation_form')->where('id' , $id)->first();
        $images = explode(',', $query->image);
        return view('/seller/enquiry/file', compact('query','images'));
      
        
    }



    public function accountinglist(Request $request)
    {
        $selleremail = $request->session()->get('seller')->email;
    
        $keyword = $request->input('keyword');
        $recordsPerPage = $request->input('r_page', 15);
    
        $query = DB::table('bidding_price')
            ->select('bidding_price.*', 'seller.name', 'seller.email', 'seller.phone')
            ->leftJoin('seller', 'bidding_price.user_email', '=', 'seller.email')
            ->where('bidding_price.seller_email', $selleremail);
    
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('bidding_price.name', 'like', '%' . $keyword . '%')
                  ->orWhere('seller.email', 'like', '%' . $keyword . '%')
                  ->orWhere('bidding_price.product_name', 'like', '%' . $keyword . '%')
                  ->orWhere('bidding_price.price', 'like', '%' . $keyword . '%');
            });
        }
    
        $blogs = $query->paginate($recordsPerPage);
        $data = [
            'keyword' => $keyword,
            'r_page' => $recordsPerPage,
        ];
        return view('seller.accounting.list', compact('blogs', 'data'));
    }



    public function accepet($id , $data_id){
       
        $inserted = DB::table('bidding_price')->where('id', $id)->update(['action' => 1]);
        $inserted = DB::table('bidding_price')->where('data_id', $data_id)->update(['hide' => 1]);
        if($inserted)
        {
          return back()->with('success', 'Status Has Been Changed.');
      }else{
          return back()->with('error', 'Updation Failed.');
      }
      }





      

    
}