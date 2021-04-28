<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\User;
use App\Models\Post;

class AuthorPaid extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $author, Post $post)
    {
        $this->author = $author;
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
        $authorName = explode(' ', $this->author->name)[0];
        $postName = $this->post->name;
        $postUrl = route('post_register', [$this->post->seo_name]);

        return (new MailMessage)
                    ->subject('Pago para el congreso verificado')
                    ->greeting('Hola '.$authorName)
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
