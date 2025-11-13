<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuotationLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class QuotationLoginController extends Controller
{
    public function quotationLogin(QuotationLoginRequest $request)
    {
        if ($request->ajax()) {
    
            $seller = Seller::where('email', $request->email)->where('verify', 1)->where('status', 1)->first();
            
            if ($seller) {
                $accTypes = explode(',', $seller->acc_type);
                if (in_array('3', $accTypes) || in_array('4', $accTypes)) {
                    if (Hash::check($request->password, $seller->password)) {
                        
                        $email = $request->email;
                        $request->session()->put('seller', $seller);
                        
                        return response()->json([
                            'status'    => true,
                            'message'   => 'Login Successfull',
                            'data'      => '',
                            'url'       => $request->redirect_url,
                        ], 200);
                        
                    } else {
                        return response()->json([
                            'status' => false,
                            'errors' => [
                                'password' => ['Incorrect password']
                            ]
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'errors' => [
                            'email' => ['You are not registered with Client or Buyer']
                        ]
                    ]);
                }
                
            } else {
                
                return response()->json([
                    'status' => false,
                    'errors' => [
                        'email' => ['No account found for this email']
                    ]
                ]);
            }
        }
    }
}
