<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class WelcomeEmployeeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $password;

    /**
     * Create a new notification instance.
     */
    public function __construct($password)
    {
        $this->password = $password;
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
        $verificationUrl = URL::temporarySignedRoute(
            'employee.verify',
            now()->addHours(24),
            ['user' => $notifiable->id]
        );

        return (new MailMessage())
            ->subject('¡Bienvenido al equipo de FoodDesk!')
            ->greeting('¡Hola '.$notifiable->name.'!')
            ->line('Te damos la bienvenida al equipo de **FoodDesk Restaurant**.')
            ->line('Se ha creado una cuenta para ti con los siguientes datos:')
            ->line('**Email:** '.$notifiable->email)
            ->line('**Rol:** '.ucfirst($notifiable->role))
            ->line('**Contraseña temporal:** '.$this->password)
            ->line('Para activar tu cuenta y poder acceder al sistema, necesitas verificar tu correo electrónico.')
            ->action('Verificar Mi Cuenta', $verificationUrl)
            ->line('Este enlace expirará en 24 horas.')
            ->line('Una vez verificada tu cuenta, podrás iniciar sesión en el sistema.')
            ->line('¡Gracias por formar parte del equipo FoodDesk!')
            ->salutation('Saludos cordiales,  
Equipo FoodDesk');
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
