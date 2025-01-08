<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendUserEmailVerifyCode extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this
            ->subject('Welcome to Our Platform')
            ->view('emails.user_verify_email_code')
            ->with(['user' => $this->user,'code'=>$this->user->verify_code]);
    }

}
