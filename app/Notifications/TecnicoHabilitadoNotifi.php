<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TecnicoHabilitadoNotifi extends Notification
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
            ->greeting('Has sido habilitado')
            ->line("Estimado  $this->user_name")
            ->line("Usted ha sido habilitado en Tecnony.")
            ->line("Puede iniciar sesión en nuestro sistema haciendo clic en el siguiente botón.")
            ->action("Iniciar sesion", env('APP_FRONTEND_URL') . '/login');
    }
}
