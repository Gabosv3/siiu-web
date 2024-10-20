<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketNotification extends Notification
{
    use Queueable;

    protected $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // Añade 'mail' para enviar correos
    }

    public function toMail($notifiable)
{
    return (new MailMessage)
        ->subject('Ticket Creado, Soporte Tecnico FMO') // Asunto del correo
        ->greeting('Hola, '.$notifiable->name) // Saludo personalizado
        ->line('Nos complace informarte que el ticket #'.$this->ticket->id.' ha sido creado exitosamente.') // Mensaje principal
        ->line('Puedes ver más detalles de tu ticket y hacer seguimiento a través del siguiente enlace:') // Mensaje adicional
        ->action('Ver Ticket', url('/mytickets/'.$this->ticket->id)) // Enlace a la acción
        ->line('Gracias por usar nuestra plataforma, estamos aquí para ayudarte.') // Mensaje de cierre
        ->salutation('Saludos, El equipo de Soporte'); // Personalización del pie de página
}
    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'message' => 'El ticket #'.$this->ticket->id.' ha sido creado.',
            'url' => url('/notifications'), // URL para ver la notificación
        ];
    }

}
