<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TecnicoDeshabilitadoNotifi extends Notification
{
    use Queueable;

    // Declaración de los atributos para la clase
    private string $user_name;
    private string $observation;
    private string $email;
    private int $personal_phone;

    // Inicialización de los atributos por medio del constructor
    public function __construct(string $user_name, string $observation, string $email, int $personal_phone)
    {
        $this->user_name = $user_name;
        $this->observation = $observation;
        $this->email = $email;
        $this->personal_phone = $personal_phone;
    }

    // Se especifica el tipo de notificación
    public function via(mixed $notifiable)
    {
        return ['mail'];
    }

    // Se procede a definir el formato para el correo electrónico
    // https://laravel.com/docs/9.x/notifications#formatting-mail-messages
    public function toMail(mixed $notifiable)
    {
        return (new MailMessage)
            ->greeting('Has sido deshabilitado')
            ->line("Estimado $this->user_name")
            ->line("Usted ha sido deshabilitado de Tecnony.")
            ->line("Por el motivo de: ")
            ->line("$this->observation")
            ->line("Comunícate al siguiente correo o número para tratar el asunto.")
            ->line("Correo: $this->email")
            ->line("Numero: $this->personal_phone");
    }
}
