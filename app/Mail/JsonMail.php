<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JsonMail extends Mailable
{
    use Queueable, SerializesModels;

    public $json;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($json)
    {
        $this->json = $json;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.json')
                    ->with('content', $this->json);
    }
}
