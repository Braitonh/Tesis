<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;
    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        $url = url('/reset-password/'.$this->token.'?email='.urlencode($notifiable->email));

        return (new MailMessage())
            ->subject('Restablecer tu contraseña - FoodDesk')
            ->greeting('¡Hola '.$notifiable->name.'!')
            ->line('Recibimos una solicitud para restablecer la contraseña de tu cuenta en **FoodDesk Restaurant**.')
            ->action('Restablecer Contraseña', $url)
            ->line('Este enlace expirará en 60 minutos por seguridad.')
            ->line('Si no solicitaste restablecer tu contraseña, puedes ignorar este mensaje de forma segura.')
            ->salutation('¡Gracias por elegirnos!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [

        ];
    }
}




