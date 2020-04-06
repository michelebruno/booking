<?php

namespace App\Notifications;

use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;

class Welcome extends Notification
{
    use Queueable;

    public $sendPasswordResetLink;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(bool $sendPasswordResetLink = false)
    {
        $this->sendPasswordResetLink = $sendPasswordResetLink;
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
        $nome = $notifiable->username ? $notifiable->username : $notifiable->email;

        $message = (new MailMessage)
            ->greeting("Benvenuto!")
            ->line("Ciao $nome, ")
            ->line("ti diamo il benvenuto sul nostro software.");

        $this->sendPasswordResetLink && $this->appendResetPasswordAction($message, $notifiable);

        return $message
            ->line('Grazie per esserti iscritto.')
            ->salutation("A presto,\nturismo.bologna.it");
    }

    public function appendResetPasswordAction(MailMessage &$message, $notifiable)
    {
        $token = Password::broker()->createToken($notifiable);

        $url = route(
            'password.reset',
            ['token' => $token, 'email' => $notifiable->getEmailForPasswordReset()]
        );

        return $message
            ->line("Non hai ancora una password. Usa questo link per impostarne una.")
            ->action("Scegli la tua password", $url);
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
