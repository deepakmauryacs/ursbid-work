<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailController extends Controller
{


    public function showForm()
    {
        return view('frontend/test');
    }




    public function sendEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'mail' => 'required|email',
        ]);

        $client = new \GuzzleHttp\Client();
        $apiKey = env('RESEND_API_KEY');
        $url = 'https://api.resend.com/emails';

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'from' => $validated['mail'], // Sender email selected from dropdown
                    'to' => $validated['email'], // Recipient email
                    'subject' => $validated['subject'], // Email subject
                    'html' => "<p>{$validated['content']}</p>", // Email content
                ],
            ]);

            return back()->with('success', 'Email sent successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }


}
