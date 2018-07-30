<?php

namespace App\Controllers\Auth;

use App\User;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\RequestEvent;
use Psr\Http\Message\ServerRequestInterface;

class IdpGrant extends PasswordGrant
{
    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client)
    {
        $username = $this->getRequestParameter('username', $request);
        if (is_null($username)) {
            throw OAuthServerException::invalidRequest('username');
        }

        $custom_hash_token = $this->getRequestParameter('hash_token', $request);
        if (is_null($custom_hash_token)) {
            throw OAuthServerException::invalidRequest('identifier');
        }

        $credentials = [
            'username' => $username,
        ];

        $user = User::where($credentials)->first();

        if ($user instanceof User === false) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));

            throw OAuthServerException::invalidCredentials();
        }

        return $user;
    }
    public function getIdentifier()
    {
        return 'idp';
    }
}