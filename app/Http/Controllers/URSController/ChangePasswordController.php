<?php

namespace App\Http\Controllers\URSController;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function show(Request $request)
    {
        $seller = $request->session()->get('seller');

        if (! $seller) {
            return redirect()->route('seller-login');
        }

        return view('ursdashboard.setting.change-password.index');
    }

    public function update(Request $request)
    {
        $seller = $request->session()->get('seller');

        if (! $seller) {
            return redirect()->route('seller-login');
        }

        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:8', 'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\\d)(?=.*[^A-Za-z\\d]).+$/'],
        ], [
            'current_password.required' => 'Current password field is required.',
            'password.required' => 'New password field is required.',
            'password.confirmed' => 'The new password confirmation does not match.',
            'password.min' => 'New password must be at least 8 characters long.',
            'password.regex' => 'New password must contain uppercase, lowercase, number, and special character.',
        ]);

        $sellerRecord = Seller::find($seller->id);

        if (! $sellerRecord) {
            return back()->withErrors([
                'current_password' => 'Unable to locate your account. Please contact support.',
            ]);
        }

        if (! Hash::check($request->current_password, $sellerRecord->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ])->withInput();
        }

        $sellerRecord->password = Hash::make($request->password);
        $sellerRecord->save();

        $request->session()->put('seller', $sellerRecord);

        return back()->with('success', 'Password updated successfully.');
    }
}
