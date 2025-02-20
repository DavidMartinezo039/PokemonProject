<?php

namespace App\Mail;

use App\Models\UserSet;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserSetCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userSet;

    /**
     * Create a new message instance.
     */
    public function __construct(UserSet $userSet)
    {
        $this->userSet = $userSet;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Tu nuevo set ha sido creado')
            ->view('emails.user_set_created');
    }
}
