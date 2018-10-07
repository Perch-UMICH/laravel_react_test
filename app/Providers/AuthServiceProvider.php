<?php

namespace App\Providers;

use App\Controllers\Auth\IdpGrant;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Bridge\UserRepository;
use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot() {
        $this->registerPolicies();

        app(AuthorizationServer::class)->enableGrantType(
            new IdpGrant(
                $this->app->make(UserRepository::class),
                $this->app->make(RefreshTokenRepository::class)
            ), Passport::tokensExpireIn()
        );

        Passport::routes();
        Passport::tokensExpireIn();
        Passport::refreshTokensExpireIn();

        //
    }
}
