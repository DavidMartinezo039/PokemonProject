<?php

namespace App\Events;

use App\Models\UserSet;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GeneratePDF
{
    use Dispatchable, SerializesModels;

    public $userSet;

    /**
     * Create a new event instance.
     */
    public function __construct(UserSet $userSet)
    {
        $this->userSet = $userSet;
    }
}
