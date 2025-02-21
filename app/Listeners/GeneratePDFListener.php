<?php

namespace App\Listeners;

use App\Events\GeneratePDF;
use App\Jobs\GenerateUserSetPdf;

class GeneratePDFListener
{
    /**
     * Handle the event.
     */
    public function handle(GeneratePDF $event): void
    {
        $userSet = $event->userSet;

        GenerateUserSetPdf::dispatch($userSet);
    }
}
