<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $studentName,
        public string $email,
        public string $password,
        public array  $enrolledCourses
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your LMS Account is Ready — Welcome!');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.student_approved');
    }
}
