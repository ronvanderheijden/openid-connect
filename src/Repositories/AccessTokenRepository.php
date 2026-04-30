<?php

declare(strict_types=1);

namespace OpenIDConnect\Repositories;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use OpenIDConnect\Entities\AccessTokenEntity;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    /**
     * @param ScopeEntityInterface[] $scopes
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, ?string $userIdentifier = null): AccessTokenEntityInterface
    {
        $accessToken = new AccessTokenEntity();
        $accessToken->setClient($clientEntity);
        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }
        $accessToken->setUserIdentifier((string) $userIdentifier);

        return $accessToken;
    }

    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void
    {
    }

    public function revokeAccessToken(string $tokenId): void
    {
    }

    public function isAccessTokenRevoked(string $tokenId): bool
    {
        return false;
    }
}
