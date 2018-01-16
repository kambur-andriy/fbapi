<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string Activation link
     */
    public $resetLink;

    /**
     * Create a new message instance.
     *
     * @param string $resetToken
     *
     */
    public function __construct($resetToken)
    {
        $this->resetLink = action('IndexController@resetPassword', ['rt' => $resetToken]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Reset password')
            ->from(env('CONTACT_EMAIL'))
            ->replyTo(env('CONTACT_EMAIL'))
            ->view('emails.reset-password');
    }
}
