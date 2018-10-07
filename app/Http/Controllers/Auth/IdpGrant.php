<?php

namespace App\Controllers\Auth;

use App\User;
use App\Http\Controllers\UserController;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\RequestEvent;
use Psr\Http\Message\ServerRequestInterface;

use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;

class IdpGrant extends AbstractGrant
{
    /**
     * IdpGrant constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->setUserRepository($userRepository);
    }

    /**
     * {@inheritdoc}
     */
    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        \DateInterval $accessTokenTTL
    ) {
        // Validate request
        // WARNING: Client cannot be validated as it is a public client. Client id is easily impersonated
        $client = $this->validatePublicClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request, $this->defaultScope));
        $user = $this->validateUser($request);

        // Finalize the requested scopes
        $finalizedScopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client, $user->getIdentifier());

        // Issue and persist new tokens
        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $user->getIdentifier(), $finalizedScopes);

        // Inject tokens into response
        $responseType->setAccessToken($accessToken);

        return $responseType;
    }


    /**
     * Validate the non-secret client.
     * WARNING: This is not a security check! Public clients are easily impersonated
     *
     * @param ServerRequestInterface $request
     *
     * @throws OAuthServerException
     *
     * @return ClientEntityInterface
     */
    protected function validatePublicClient(ServerRequestInterface $request)
    {
        $clientId = $this->getRequestParameter('client_id', $request);
        if (is_null($clientId)) {
            throw OAuthServerException::invalidRequest('client_id');
        }

        $client = $this->clientRepository->getClientEntity(
            $clientId,
            $this->getIdentifier(),
            null,
            false
        );

        if ($client instanceof ClientEntityInterface === false) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::CLIENT_AUTHENTICATION_FAILED, $request));
            throw OAuthServerException::invalidClient();
        }

//        // If a redirect URI is provided ensure it matches what is pre-registered
//        $redirectUri = $this->getRequestParameter('redirect_uri', $request, null);
//        if ($redirectUri !== null) {
//            if (
//                is_string($client->getRedirectUri())
//                && (strcmp($client->getRedirectUri(), $redirectUri) !== 0)
//            ) {
//                $this->getEmitter()->emit(new RequestEvent(RequestEvent::CLIENT_AUTHENTICATION_FAILED, $request));
//                throw OAuthServerException::invalidClient();
//            } elseif (
//                is_array($client->getRedirectUri())
//                && in_array($redirectUri, $client->getRedirectUri()) === false
//            ) {
//                $this->getEmitter()->emit(new RequestEvent(RequestEvent::CLIENT_AUTHENTICATION_FAILED, $request));
//                throw OAuthServerException::invalidClient();
//            }
//        }

        return $client;
    }

    /**
     * @param ServerRequestInterface $request
     * @return mixed
     */
    protected function validateUser(ServerRequestInterface $request)
    {
        $idp = $this->getRequestParameter('idp', $request);
        $token = $this->getRequestParameter('idpToken', $request);
        $register = $this->getRequestParameter('register', $request);
        if($idp === "google") {
            $client = new \Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);

            // Google verifies 'iss' (google's source signature) and 'exp' (the token expiration)
            try{
                $payload = $client->verifyIdToken($token);
            } catch(Exception $e) {
                throw OAuthServerException::invalidRequest('token: ' . $token);
            }

            if($payload) {
                $username = $payload('sub');
                $email = $payload('email');
                // $aud = $payload('aud');
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

        // Register a new google user
        if($register) {
            $uc = new UserController();

            // Will work even if the user already exists
            $user = $uc->registerIdp($idp, $username, $email);
        } else {
            // Authenticate existing user
            $credentials = [
                'username' => $username,
                'email' => $email
            ];

            $user = User::where($credentials)->first();

            if ($user instanceof User === false) {
                $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));

                throw OAuthServerException::invalidCredentials();
            }
        }
        return $user;
    }

    public function getIdentifier()
    {
        return 'idp';
    }

    /**
     * @param RefreshTokenRepositoryInterface $refreshTokenRepository
     *
     * @throw \LogicException
     */
    public function setRefreshTokenRepository(RefreshTokenRepositoryInterface $refreshTokenRepository)
    {
        throw new \LogicException('The Implicit Grant does not return refresh tokens');
    }
}