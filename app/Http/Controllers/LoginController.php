<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Models\User;

class LoginController extends Controller
{
    public function login()
    {

      return view('admin/login');
    }

    public function authenticate(Request $request)
    {
      $validator = $request->validate([
        'email' => 'required',
        'password' => 'required'
    ]);

    $admin = User::where('email', $request->email)->first();
    if ($admin) {
        if ($request->password == $admin->password) {
            $request->session()->put('admin', $admin);
            return redirect('dashboard');
        } else {
            $request->session()->flash('fail', 'Incorrect password');
            return redirect('admin')->withErrors($validator)->withInput();
        }
    } else {
        $request->session()->flash('fail', 'No account found for this email');
        return redirect('admin')->withErrors($validator)->withInput();
      
    }
    }

   

}