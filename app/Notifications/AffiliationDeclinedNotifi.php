<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AffiliationDeclinedNotifi extends Notification
{
    use Queueable;
    // Declaración de los atributos para la clase
    private string $user_name;
    private string $observation;

    // Inicialización de los atributos por medio del constructor
    public function __construct(string $user_name, string $observation)
    {
        $this->user_name = $user_name;
        $this->observation = $observation;
    }

    // Se especifica el tipo de notificación
    public function via($notifiable)
    {
        return ['mail'];
    }

    // Se procede a definir el formato para el correo electrónico
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Afiliación Rechazada!')
            ->line("Usuario $this->user_name")
            ->line("Su solicitud de afiliación ha sido rechazada.")
            ->line("Debido a las siguientes observaciones:")
            ->line("$this->observation")
            ->line("Inicia sesión y corrige los datos de tu afiliación:")
            ->action("Iniciar sesion", env('APP_FRONTEND_URL') . '/login')
            ->line("Importante: Si el en el caso que su solicitud no ha sido aprobada despues de las correciones comuníquese con el administrador que le atendio.");
    }
}
