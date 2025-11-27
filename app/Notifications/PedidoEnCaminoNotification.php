<?php

namespace App\Notifications;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PedidoEnCaminoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $pedido;

    /**
     * Create a new notification instance.
     */
    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;
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
        $deliveryName = $this->pedido->delivery ? $this->pedido->delivery->name : 'Nuestro equipo de delivery';
        
        $mail = (new MailMessage())
            ->subject('¡Tu pedido está en camino! - FoodDesk')
            ->greeting('¡Hola '.$notifiable->name.'!')
            ->line('¡Buenas noticias! Tu pedido está en camino hacia tu dirección.')
            ->line('**Número de Pedido:** '.$this->pedido->numero_pedido)
            ->line('**Total:** $'.number_format($this->pedido->total, 2, '.', ','))
            ->line('**Delivery asignado:** '.$deliveryName);

        if ($this->pedido->direccion_entrega) {
            $mail->line('**Dirección de entrega:** '.$this->pedido->direccion_entrega);
        }

        $mail->line('Tu pedido salió de nuestro restaurante y está siendo entregado por nuestro equipo de delivery.')
            ->line('Por favor, asegúrate de estar disponible para recibir tu pedido.');

        // Agregar enlace para ver detalles del pedido si existe la ruta
        if (route('cliente.pedido.confirmacion', $this->pedido, false)) {
            $mail->action('Ver Detalles del Pedido', route('cliente.pedido.confirmacion', $this->pedido));
        }

        $mail->line('¡Gracias por elegir FoodDesk!')
            ->salutation('Saludos cordiales,  
Equipo FoodDesk');

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'pedido_id' => $this->pedido->id,
            'numero_pedido' => $this->pedido->numero_pedido,
            'estado' => $this->pedido->estado,
        ];
    }
}

