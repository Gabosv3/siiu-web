<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends Notification
{
    public $token;

    // Constructor para recibir el token
    public function __construct($token)
    {
        $this->token = $token;
    }

    // El canal que utilizará la notificación
    public function via($notifiable)
    {
        return ['mail'];
    }

    // Definir el contenido del correo electrónico
    public function toMail($notifiable)
    {
        $url = url(config('app.url') . '/password/reset/' . $this->token . '?email=' . urlencode($notifiable->email));
    
        return (new MailMessage)
                    ->subject(__('passwords.subject')) // Esto se traducirá según el archivo de idiomas
                    ->greeting(__('passwords.greeting')) // Traducción de saludo
                    ->line(__('passwords.line1')) // Mensaje que indica que se ha solicitado un restablecimiento
                    ->action(__('passwords.action'), $url) // Botón con la acción para restablecer
                    ->line(__('passwords.expiry')) // Mensaje de expiración del enlace
                    ->line(__('passwords.warning')); // Mensaje de advertencia si no se solicitó el restablecimiento
    }
}
