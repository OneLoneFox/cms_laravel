<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\User;
use App\Models\Post;
use App\Models\Article;

class AuthorArticleAccepted extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $author, Article $article, Post $post)
    {
        $this->author = $author;
        $this->post = $post;
        $this->article = $article;
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
        $status = Article::STATUS_MAP_ES[$this->article->status];
        return (new MailMessage)
                    ->greeting('Hola ', $authorName)
                    ->line("Tu articulo para $postName ha sido revisado y el status ha sido actualizado.")
                    ->line('El status de tu articulo es: '.$this->article->status)
                    ->action('Ver congreso', $postUrl);
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
