<?php

namespace App\Mail;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PostShared extends Mailable
{
    use Queueable, SerializesModels;

    public Post $post;
    public User $user;

    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Nuevo contenido: {$this->post->titulo}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.post-shared',
        );
    }
}
