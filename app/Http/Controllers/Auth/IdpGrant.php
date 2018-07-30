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
        $idp = $this->getRequestParameter('idp', $request);
        $token = $this->getRequestParameter('idpToken', $request);
        if($idp === "google") {
            $client = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);

            // Google verifies 'iss' (google's source signature) and 'exp' (the token expiration)
            $payload = $client->verifyIdToken($token);
            if($payload) {
                $username = $payload('sub');
                $email = $payload('email');
                $aud = $payload('aud');
                // $payload('hd') // G-suite domain
                // $payload('name')
                // $payload('given_name')
                // $payload('family_name')

            } else {
                // Invalid google token
                throw OAuthServerException::invalidRequest('idpToken');
            }
        } else {
            throw OAuthServerException::invalidRequest('idp');
        }

        if (is_null($username)) {
            throw OAuthServerException::invalidRequest('username');
        }

        $credentials = [
            'username' => $username,
            'email' => $email
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