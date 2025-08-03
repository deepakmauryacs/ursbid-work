<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class PasswordResetController extends Controller
{
    public function showForgotForm()
    {
        return view('frontend.forgot-password');
    }

    public function submitForgotForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'g-recaptcha-response' => 'required'
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
        $user =  DB::table('seller')
                ->where('email', $request->email)
                ->where('verify', 1)
                ->first();

           
        
        if (!$user) {
            return back()->withErrors(['email' => 'Email not found.']);
        }

        $token = Str::random(64);
        $email = $request->email;

        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        $link = url('/reset-password/' . $token . '?email=' . urlencode($email));

        // Send mail
        Mail::to($email)->send(new \App\Mail\ForgotPasswordMail($link));

        return back()->with('success', 'Reset link sent to your email.');
    }

    public function showResetForm(Request $request, $token)
    {
        $email = $request->query('email');
        return view('frontend.reset', compact('token', 'email'));
    }

    public function submitResetForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z\d])[A-Za-z\d\W]{8,}$/'
            ]

    ], [
        'password.required' => 'Password field is required.',
        'password.min' => 'Password must be at least 8 characters long.',
        'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).'
    ]);

        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset || Carbon::parse($reset->created_at)->addMinutes(5)->isPast()) {
            return back()->withErrors(['token' => 'Invalid or expired token.']);
        }

        DB::table('seller')
            ->where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('seller-register')->with('success', 'Password successfully reset.');
    }
}
