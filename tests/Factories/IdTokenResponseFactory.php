<?php

declare(strict_types=1);

namespace OpenIDConnect\Tests\Factories;

use Lcobucci\JWT\Configuration;
use League\OAuth2\Server\ResponseTypes\BearerTokenResponse;
use OpenIDConnect\ClaimExtractor;
use OpenIDConnect\IdTokenResponse;
use OpenIDConnect\Interfaces\IdentityRepositoryInterface;

class IdTokenResponseFactory
{
    private function build(
        IdentityRepositoryInterface $identityRepository,
        ClaimExtractor $claimExtractor,
        ?Configuration $config = null,
        ?string $issuer = null,
    ): BearerTokenResponse {
        return new IdTokenResponse(
            $identityRepository,
            $claimExtractor,
            $config ?? ConfigutationFactory::default(),
            $issuer,
        );
    }

    public static function default(
        IdentityRepositoryInterface $identityRepository,
        ClaimExtractor $claimExtractor,
        ?string $issuer = null,
    ): BearerTokenResponse {
        return (new static())->build($identityRepository, $claimExtractor, null, $issuer);
    }

    public static function withConfig(
        IdentityRepositoryInterface $identityRepository,
        ClaimExtractor $claimExtractor,
        Configuration $config,
        ?string $issuer = null,
    ): BearerTokenResponse {
        return (new static())->build($identityRepository, $claimExtractor, $config, $issuer);
    }
}
