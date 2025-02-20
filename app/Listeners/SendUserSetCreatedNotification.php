<?php

namespace App\Listeners;

use App\Events\UserSetCreated;
use App\Mail\UserSetCreatedMail;
use Illuminate\Support\Facades\Mail;

class SendUserSetCreatedNotification
{
    /**
     * Handle the event.
     */
    public function handle(UserSetCreated $event): void
    {
        $user = $event->userSet->user;
        Mail::to($user->email)->send(new UserSetCreatedMail($event->userSet));
    }
}
