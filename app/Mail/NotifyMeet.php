<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyMeet extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $date_meet;
    public $link_meet;

    public function __construct($name, $date_meet, $link_meet)
    {
        $this->name = $name;
        $this->date_meet = $date_meet;
        $this->link_meet = $link_meet;
    }

    public function build()
    {
        return $this->view('emails.notify-meet', [
            'name' => $this->name,
            'date_meet' => $this->date_meet,
            'link_meet' => $this->link_meet
        ])
            ->subject('Notificación de Reunión');
    }
}
