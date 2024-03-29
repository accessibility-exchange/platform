<?php

namespace App\Mail;

use App\Models\Quiz;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuizResults extends Mailable
{
    use Queueable, SerializesModels;

    public Quiz $quiz;

    public string $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Quiz $quiz, string $name)
    {
        $this->quiz = $quiz;
        $this->name = $name;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: __('Quiz Results for :name', ['name' => $this->name]),
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'emails.quiz.results',
            with: [
                'course' => $this->quiz->course->title,
                'name' => $this->name,
            ],
        );
    }
}
