<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\User;
use App\Models\Post;

class ParticipantPaid extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $participant, Post $post)
    {
        $this->participant = $participant;
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $participantName = explode(' ', $this->participant->name)[0];
        $postName = $this->post->name;
        $postUrl = route('post_register', [$this->post->seo_name]);

        return (new MailMessage)
                    ->subject('Pago para el congreso verificado')
                    ->greeting('Hola '.$participantName)
                    ->line('Tu pago para el congreso '.$postName.' ha sido verificado con exito.')
                    ->action('Ver congreso', $postUrl)
                    ->line('¡Gracias por registrarte, te estaremos esperando!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
