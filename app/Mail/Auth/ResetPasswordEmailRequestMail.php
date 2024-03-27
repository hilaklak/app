<?php

namespace App\Mail\User\Auth;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordEmailRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $code)
    {
        $this->user = $user;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.user.auth.reset-password-request-email')
            ->subject('subject');
    }
}
