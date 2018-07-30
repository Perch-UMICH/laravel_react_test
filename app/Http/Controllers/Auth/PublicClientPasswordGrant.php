<?php
/**
 * OAuth 2.0 Password grant.
 *
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace League\OAuth2\Server\Grant;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Http\Controllers\UserController;

/**
 * Password grant class.
 */
class PublicClientPasswordGrant extends AbstractGrant
{
    /**
     * @param UserRepositoryInterface         $userRepository
     * @param RefreshTokenRepositoryInterface $refreshTokenRepository
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
        $client = $this->validatePublicClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request, $this->defaultScope));
        $user = $this->validateUser($request, $client);

        // Finalize the requested scopes
        $finalizedScopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client, $user->getIdentifier());

        // Issue and persist new tokens
        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $user->getIdentifier(), $finalizedScopes);

        // Inject tokens into response
        $responseType->setAccessToken($accessToken);

        return $responseType;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ClientEntityInterface  $client
     *
     * @throws OAuthServerException
     *
     * @return UserEntityInterface
     */
    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client)
    {
        $username = $this->getRequestParameter('username', $request);
        if (is_null($username)) {
            throw OAuthServerException::invalidRequest('username');
        }

        $password = $this->getRequestParameter('password', $request);
        if (is_null($password)) {
            throw OAuthServerException::invalidRequest('password');
        }

        $credentials = [
            'username' => $username,
            'password' => bcrypt($password),
            'provider' => 'password',
        ];

        $user = UserController::getUser
        if ($user instanceof UserEntityInterface === false) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));
            throw OAuthServerException::invalidCredentials();
        }

        return $user;
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
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return 'pcPassword';
    }
}
