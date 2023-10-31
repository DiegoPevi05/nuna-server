<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReplyJoinUs extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $city;
    public $phone;

    public function __construct($name, $email, $city, $phone)
    {
        $this->name = $name;
        $this->email = $email;
        $this->city = $city;
        $this->phone = $phone;
    }

    public function build()
    {
        return $this->view('emails.joinus-form', [
            'name' => $this->name,
            'email' => $this->email,
            'city' => $this->city,
            'phone' => $this->phone,
        ])
            ->subject('Una persona quiere unirse a Nuna');
    }
}
