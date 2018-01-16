<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string User name
     */
    public $userName;

    /**
     * @var string User email
     */
    public $userEmail;

    /**
     * @var string User message
     */
    public $userMessage;

    /**
     * Create a new message instance.
     *
     * @param string $userName
     * @param string $userEmail
     * @param string $userMessage
     *
     */
    public function __construct($userName, $userEmail, $userMessage)
    {
       $this->userName = $userName;
       $this->userEmail = $userEmail;
       $this->userMessage = $userMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.contact-us');
    }
}
