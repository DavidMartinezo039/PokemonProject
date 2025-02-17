<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\UserSet;
use App\Models\User;

class UserSetUpdated
{
    use Dispatchable, SerializesModels;

    public $user;
    public $userSet;
    public $action;
    public $cardId;

    public function __construct(User $user, UserSet $userSet, string $action, string $cardId = null)
    {
        $this->user = $user;
        $this->userSet = $userSet;
        $this->action = $action;
        $this->cardId = $cardId;
    }
}
