<?php

declare(strict_types=1);

namespace OpenIDConnect\Repositories;

use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use OpenIDConnect\Entities\AuthCodeEntity;

class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    public function getNewAuthCode(): AuthCodeEntityInterface
    {
        return new AuthCodeEntity();
    }

    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity): void
    {
    }

    public function revokeAuthCode(string $codeId): void
    {
    }

    public function isAuthCodeRevoked(string $codeId): bool
    {
        return false;
    }
}
