<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TecnicoRegistroNotifi extends Notification
{
    use Queueable;
        // Declaración de los atributos para la clase
        private string $user_name;
        private string $role_name;

        // Inicialización de los atributos por medio del constructor
        public function __construct(string $user_name, string $role_name)
        {
            $this->user_name = $user_name;
            $this->role_name = $role_name;
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
                ->greeting('Registro completo!')
                ->line("Bienvenido $this->user_name")
                ->line("Tú has sido registrado en Tecnony.")
                ->line("Detalles del registro: ")
                ->line("Tu rol de usuario es: $this->role_name")
                ->line("Puede iniciar sesión en nuestro sistema haciendo clic en el siguiente botón.")
                ->action("Iniciar sesion", env('APP_FRONTEND_URL') . '/login')
                ->line("Recuerde: no compartir tu contraseña.");
        }
}
