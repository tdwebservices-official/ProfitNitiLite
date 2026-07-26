<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class NewPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $newPassword;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $newPassword)
    {
        $this->user = $user;
        $this->newPassword = $newPassword;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your New Password')
            ->view('emails.new-password')
            ->with([
                'user'        => $this->user,
                'newPassword' => $this->newPassword,
            ]);
    }
}