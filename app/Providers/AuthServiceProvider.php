<?php

namespace App\Providers;

use Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /** 
     * The policy mappings for the application. 
     * 
     * 
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];
    /** 
     * Register any authentication / authorization services. 
     * 
     * @return void 
     */
    public function boot()
    {
        $this->registerPolicies();


        // define a admin user role 
        Gate::define('create_user_roles', function ($user) {
            return $user->role == 1;
        });

        //define a author user role 
        Gate::define('isSubscribed', function ($user) {
            return $user->role == 2;
        });

        // define a editor role 
        Gate::define('isNonSubscribed', function ($user) {
            return $user->role == 3;
        });
    }
}
