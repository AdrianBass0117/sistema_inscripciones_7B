<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;
    public $name;

    /**
     * Create a new notification instance.
     */
    public function __construct($token, $name)
    {
        $this->token = $token;
        $this->name = $name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Generamos la URL de reseteo.
        // El notifiable (Usuario, Comite, o Supervisor) ya tiene el email.
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        // Usamos la plantilla Blade que crearemos en el siguiente paso
        return (new MailMessage)
            ->subject('Restablece tu Contraseña')
            ->view('emails.reset_password', [
                'url' => $url,
                'name' => $this->name
            ]);
    }
}