<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Events\UserSetUpdated;
use App\Listeners\NotifyUserSetChange;
use Illuminate\Support\Facades\Event;

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
    public function boot()
    {
        Event::listen(NotifyUserSetChange::class);
    }
}
