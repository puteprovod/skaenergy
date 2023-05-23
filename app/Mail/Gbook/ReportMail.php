<?php

namespace App\Mail\Gbook;

use App\Models\GbookPost;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public GbookPost $post;
    public User $reportedUser;
    public User|null $postUser;

    /**
     * Create a new message instance.
     */
    public function __construct(GbookPost $post, User $reportedUser)
    {
        $this->post = $post;
        $this->reportedUser = $reportedUser;
        if ($user = User::find($post->user_id)) {
            $this->postUser =  $user;
        }
        else
        {
            $this->postUser = null;
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Репорт от посетителя ' . $this->reportedUser->nick,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.gbook.report',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}//
