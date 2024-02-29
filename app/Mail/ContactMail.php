<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   */
  public $name;
  public $email;
  public $phone;
  public $body;

  public function __construct($name, $email, $phone, $body)
  {
    $this->name = $name;
    $this->email = $email;
    $this->phone = $phone;
    $this->body = $body;
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      subject: 'Contact Mail',
    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
    return new Content(
      view: 'contact',
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
}
