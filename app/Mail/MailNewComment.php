<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use App\Models\Article;

class MailNewComment extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Article $article)
    {
        // $this->article=$article;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('papievamary@mail.ru')
        );
    }

    /**
    * Get the message content definition.
    */
    public function content(): Content
    {
     return new Content(
            view: 'mail.comment',
         );
    }
    // public function build()
    // {
    //     return $this-> from(env('MAIL_USERNAME'))
    //                 ->with(['article'=>$this->$article])
    //                 ->view('mail.comment') ;
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
