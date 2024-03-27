<?php

namespace App\Notifications\Site\Auth;

use App\Channels\SMSKavenegarChannel;
use App\Enums\ForgotPasswordTypeEnum;
use App\Mail\Site\Auth\ResetPasswordRequestMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UserResetPasswordRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $token;

    protected $type;

    public function __construct($token, $type)
    {
        $this->token = $token;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $channel = $this->type === ForgotPasswordTypeEnum::EMAIL ? ['mail'] : [SMSKavenegarChannel::class];

        return $channel;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('auth.password.reset.email', $this->token);

        return (new ResetPasswordRequestMail($url))->to($notifiable->email);
    }

    /**
     * Get the sms representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SMSMessage
     */
    public function toSMS($notifiable)
    {
        return [
            'receptor' => $notifiable->mobile,
            'message' => 'پیام',
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
