<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketAssignedNotification extends Notification
{
    use Queueable;

    protected $ticket;
    protected $technician; // Agregamos el técnico

    public function __construct($ticket, $technician)
    {
        $this->ticket = $ticket;
        $this->technician = $technician; // Guardamos el técnico
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Tu ticket ha sido asignado a un técnico')
            ->greeting('Hola, ' . $notifiable->name)
            ->line('Tu ticket #' . $this->ticket->id . ' ha sido asignado a un técnico.')
            ->line('👨‍🔧 Técnico Asignado: ' . $this->technician->user->name) // Agrega el técnico asignado
            ->line('Prioridad: ' . ucfirst($this->ticket->priority))
            ->line('Estado: ' . ucfirst($this->ticket->status))
            ->action('Ver Ticket', url('/mytickets/' . $this->ticket->id))
            ->line('Gracias por usar nuestra plataforma.');
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'message' => 'Tu ticket #' . $this->ticket->id . ' ha sido asignado a ' . $this->technician->name,
            'url' => url('/mytickets/' . $this->ticket->id),
        ];
    }
}

