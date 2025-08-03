<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Contact_mail extends Mailable
{ 
    public $data;
    
    public function __construct($data)
    {
        $this->data = $data; 
    }

    public function build()
    {
        info($this->data);
        return $this->subject('Contact Us Enquiry.')
            ->view('frontend.email.contact');
    }
}
