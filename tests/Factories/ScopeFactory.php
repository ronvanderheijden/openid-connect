<?php

declare(strict_types=1);

namespace OpenIDConnect\Tests\Factories;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use OpenIDConnect\Entities\ScopeEntity;

class ScopeFactory
{
    private function build(string $identifier): ScopeEntityInterface
    {
        $scope = new ScopeEntity();
        $scope->setIdentifier($identifier);
        return $scope;
    }

    public static function default(string $identifier): ScopeEntityInterface
    {
        return (new static())->build($identifier);
    }
}
