<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevoComprobanteDePagoNotifi extends Notification
{
    use Queueable;

    // Declaración de los atributos para la clase
    private string $user_cli;
    private string $device;
    private string $model;
    private string $user_tec;

    // Inicialización de los atributos por medio del constructor
    public function __construct(string $user_cli, string $device, string $model, string $user_tec)
    {
        $this->user_cli = $user_cli;
        $this->device = $device;
        $this->model = $model;
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
            ->greeting('Comprobante de pago recibido!')
            ->line("Estimado técnico $this->user_tec")
            ->line("De parte del cliente $this->user_cli ha recibido un comprobante de pago por el servicio realizado al:")
            ->line("Dispositivo: $this->device")
            ->line("Modelo: $this->model")
            ->line("Ingrese a Tecnony para visualizar el comprobante de pago y marcar como pagado al servicio realizado.")
            ->action("Iniciar sesion", env('APP_FRONTEND_URL') . '/login');
    }
}
