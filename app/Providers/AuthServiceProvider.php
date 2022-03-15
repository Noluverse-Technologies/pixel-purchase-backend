<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Carbon;
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

        //below code force expires the token after 1 hour
        Passport::routes();
        // Passport::tokensExpireIn(Carbon::now()->addSeconds(200));
        // Passport::personalAccessTokensExpireIn(Carbon::now()->addHours(200));
        // Passport::refreshTokensExpireIn(Carbon::now()->addHours(200));

        // define a admin user role 
        Gate::define('create_user_roles', function ($user) {
            return $user->role == 1;
        });

        //define a author user role 
        Gate::define('create_license_pixels', function ($user) {
            return $user->role == 1;
        });

        // define a editor role 
        Gate::define('can_manage_user_subscription', function ($user) {
            return $user->role == 1;
        });
        // define a editor role 
        Gate::define('can_view_transactions', function ($user) {
            if ($user->role == 2 || $user->role == 1) {
                return true;
            }
        });
    }
}
