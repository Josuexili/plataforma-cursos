<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::define('is-admin', fn (User $user): bool => $user->hasRole('admin'));

        Gate::define('is-owner', function (User $user, mixed $resource): bool {
            if ($user->hasRole('admin')) {
                return true;
            }

            return is_object($resource)
                && isset($resource->user_id)
                && (int) $resource->user_id === (int) $user->id;
        });
    }
}
