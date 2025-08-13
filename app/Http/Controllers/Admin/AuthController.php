<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('ursbid-admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        // Only allow Super Admin (user_type = 1)
        if (Auth::attempt(array_merge($credentials, ['user_type' => 1]))) {
            return redirect()->route('super-admin.dashboard'); // your admin dashboard route
        }

        return back()->withErrors(['email' => 'Invalid credentials or not a Super Admin']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
