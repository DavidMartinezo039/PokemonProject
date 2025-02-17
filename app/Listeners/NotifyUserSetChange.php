<?php

namespace App\Listeners;

use App\Events\UserSetUpdated;
use Illuminate\Support\Facades\Log;

class NotifyUserSetChange
{
    public function handle(UserSetUpdated $event)
    {
        if ($event->action === 'created') {
            Log::info("User {$event->user->id} created a new set: '{$event->userSet->name}'.");
        } elseif ($event->action === 'added_card') {
            Log::info("User {$event->user->id} added card ID {$event->cardId} to set '{$event->userSet->name}'.");
        } elseif ($event->action === 'removed_card') {
            Log::info("User {$event->user->id} removed card ID {$event->cardId} from set '{$event->userSet->name}'.");
        }
    }
}
