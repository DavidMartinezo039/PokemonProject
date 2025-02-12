<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\UserSet;
use App\Policies\UserSetPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * El mapeo de las políticas del modelo para la aplicación.
     *
     * @var array
     */
    protected $policies = [
        UserSet::class => UserSetPolicy::class,
    ];

    /**
     * Registra cualquier servicio de autenticación/autorización.
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
