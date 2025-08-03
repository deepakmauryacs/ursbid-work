<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $hashId;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($otp, $hashId)
    {
        $this->otp = $otp;
        $this->hashId = $hashId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('OTP for Account Verification')
                    ->view('emails.otp_verification');
    }
}
