<?php

namespace App\Providers;

use App\Controllers\Auth\IdpGrant;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Bridge\UserRepository;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;
/**
 * Class CustomQueueServiceProvider
 *
 * @package App\Providers
 */
class CustomGrantProvider extends PassportServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app(AuthorizationServer::class)->enableGrantType($this->makeIdpGrant(), Passport::tokensExpireIn());
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
    /**
     * Create and configure a Password grant instance.
     *
     * @return PasswordGrant
     */
    protected function makeIdpGrant()
    {
        $grant = new IdpGrant(
            $this->app->make(UserRepository::class),
            $this->app->make(RefreshTokenRepository::class)
        );
        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());
        return $grant;
    }
}