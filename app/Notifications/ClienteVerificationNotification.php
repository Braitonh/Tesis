<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClienteVerificationNotification extends Notification
{
    use Queueable;

    public $token;
    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
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
        $url = url('/email/verify/'.$this->token);

        return (new MailMessage())
            ->subject('¡Activa tu cuenta en FoodDesk!')
            ->greeting('¡Hola '.$this->user->name.'!')
            ->line('Te damos la bienvenida a **FoodDesk Restaurant**.')
            ->line('Tu cuenta ha sido creada exitosamente. Para activar tu cuenta y comenzar a disfrutar de nuestros servicios, necesitas verificar tu correo electrónico.')
            ->action('Activar Mi Cuenta', $url)
            ->line('Este enlace expirará en 24 horas por seguridad.')
            ->line('Si no creaste esta cuenta, puedes ignorar este mensaje.')
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

