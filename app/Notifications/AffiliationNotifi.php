<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AffiliationNotifi extends Notification
{
    use Queueable;
    // Declaración de los atributos para la clase
    private string $user_name;

    // Inicialización de los atributos por medio del constructor
    public function __construct(string $user_name)
    {
        $this->user_name = $user_name;
    }

    // Se especifica el tipo de notificación
    public function via(mixed $notifiable)
    {
        return ['mail'];
    }

    // Se procede a definir el formato para el correo electrónico
    public function toMail(mixed $notifiable)
    {
        return (new MailMessage)
            ->greeting('Afiliación Aprobada!')
            ->line("Felicitaciones $this->user_name")
            ->line("Su solicitud de afiliación ha sido aprobada.")
            ->line("Ahora ya eres parte de Tecnony  ")
            ->line("Inicia sesión y crea tu primer servicio:")
            ->action("Iniciar sesion", env('APP_FRONTEND_URL') . '/login');
    }
}
