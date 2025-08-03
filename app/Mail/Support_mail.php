<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Support_mail extends Mailable
{ 
    public $data;
    
    public function __construct($data)
    {
        $this->data = $data; // Assign the data to the public property
    }

    public function build()
    {
        info($this->data);
        return $this->subject('Enquiry For Customer Support.')
            ->view('frontend.email.support');
    }
}
