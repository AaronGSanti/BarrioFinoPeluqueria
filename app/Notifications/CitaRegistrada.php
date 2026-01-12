<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CitaRegistrada extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $cita)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     * Construimos el correo que se enviara al usuario cuando se registre una cita
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Cita Registrada')
            ->greeting('¡Listo!')
            ->line('Tu cita ha sido registrada exitosamente.')
            ->line('Te esperamos en la fecha y hora acordada.')
            ->line('Fecha - Hora: ' . $this->cita->hora_inicio)
            ->line('Barbero: ' . $this->cita->barbero->name)
            ->line('Total: ' . $this->cita->precio_total . ' EUR')
            ->line('Gracias por confiar en nosotros para tu estilo.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
