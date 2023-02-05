<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContratacionPagadaNotifi extends Notification
{
    use Queueable;

    // Declaración de los atributos para la clase
    private string $user_name;
    private string $user_tec;

    // Inicialización de los atributos por medio del constructor
    public function __construct(string $user_name, string $user_tec)
    {
        $this->user_name = $user_name;
        $this->user_tec = $user_tec;
    }

    // Se especifica el tipo de notificacion
    public function via(mixed $notifiable)
    {
        return ['mail'];
    }

    // Se procede a definir el formato del correo
    public function toMail(mixed $notifiable)
    {
        return (new MailMessage)
            ->greeting('Solicitud de contratación pagada')
            ->line("Estimado $this->user_name")
            ->line("De parte del técnico $this->user_tec, te doy las gracias por contratar nuestros servicios.")
            ->line("Te invitamos a ingresar a Tecnony y calificar el servicio prestado con el objetivo de mejorar la calidad de nuestros servicios.");
    }
}
