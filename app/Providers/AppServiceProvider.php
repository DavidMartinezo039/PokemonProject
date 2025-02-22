<?php

namespace App\Providers;

use App\Events\GeneratePDF;
use App\Listeners\GeneratePDFListener;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\UserSetCreated;
use App\Listeners\SendUserSetCreatedNotification;
use App\Events\UserSetUpdated;
use App\Listeners\NotifyUserSetChange;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
