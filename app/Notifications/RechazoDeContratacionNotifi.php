<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RechazoDeContratacionNotifi extends Notification
{
    use Queueable;

    // Declaración de los atributos para la clase
    private string $user_name;
    private string $observation;
    private string $user_tec;

    // Inicialización de los atributos por medio del constructor
    public function __construct(string $user_name, string $observation, string $user_tec)
    {
        $this->user_name = $user_name;
        $this->observation = $observation;
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
            ->greeting('Solicitud de contratación rechazada!')
            ->line("Estimado $this->user_name")
            ->line("De parte del técnico $this->user_tec temo notificarle que su solicitud de contratación a sido rechazada.")
            ->line("Por el motivo de: ")
            ->line("$this->observation")
            ->line("Lamentamos los inconvenientes y te invitamos a buscar otro servicio que satisfaga tus necesidades.");
    }
}
