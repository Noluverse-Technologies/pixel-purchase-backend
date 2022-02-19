<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        
        /**
         * *Definitions of gate permissions
         */
        // define a admin user role 
        Gate::define('isAdmin', function ($user) {
            return $user->role == 1;
        });

        //define a author user role 
        Gate::define('isSubscribedUser', function ($user) {
            return $user->role == 2;
        });

        // define a editor role 
        Gate::define('isNonSubscribedUser', function ($user) {
            return $user->role == 3;
        });

        //
    }
}
