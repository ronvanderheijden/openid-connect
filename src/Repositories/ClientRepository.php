<?php

declare(strict_types=1);

namespace OpenIDConnect\Repositories;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use OpenIDConnect\Entities\ClientEntity;

class ClientRepository implements ClientRepositoryInterface
{
    public function getClientEntity(string $clientIdentifier): ClientEntityInterface
    {
        $client = new ClientEntity();
        $client->setIdentifier('1');
        $client->setRedirectUri('http://example.com/callback');
        $client->setName('Example');
        return $client;
    }

    public function validateClient(string $clientIdentifier, ?string $clientSecret, ?string $grantType): bool
    {
        return true;
    }
}
