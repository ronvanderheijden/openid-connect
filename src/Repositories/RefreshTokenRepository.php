<?php

declare(strict_types=1);

namespace OpenIDConnect\Repositories;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use OpenIDConnect\Entities\RefreshTokenEntity;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    public function getNewRefreshToken(): RefreshTokenEntityInterface
    {
        return new RefreshTokenEntity();
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
    }

    public function revokeRefreshToken(string $tokenId): void
    {
    }

    public function isRefreshTokenRevoked(string $tokenId): bool
    {
        return false;
    }
}
