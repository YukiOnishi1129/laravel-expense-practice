<?php

namespace App\Mail;

use App\Models\Expense;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExpenseRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Expense $expense, public string $comment)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: '申請が差し戻されました');
    }

    public function content(): Content
    {
        return new Content(text: 'emails.expense_rejected');
    }
}
