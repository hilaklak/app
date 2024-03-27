<?php

namespace App\Notifications\User\Auth;

use App\Mail\User\Auth\VerificationEmailTokenMail;
use App\Services\GenerateTokenService;
use App\Services\VerificationEmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class VerificationEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // $code = GenerateTokenService::generate();

        // VerificationEmailService::storeToken($notifiable->id, $code);

        $activationCode = $notifiable->generateEmailVerificationToken();

        return (new VerificationEmailTokenMail($notifiable, $activationCode->code))
            ->to($notifiable->email);
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
