<?php

declare(strict_types=1);

namespace OpenIDConnect\Tests\Factories;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;

class ConfigutationFactory
{
    private function build(
        ?Key $signerKey = null
    ): Configuration {
        return Configuration::forSymmetricSigner(
            new Sha256(),
            $signerKey ?? KeyFactory::signerKeyFromFile(),
        );
    }

    public static function default(): Configuration
    {
        return (new static())->build();
    }

    public static function withSignerKey(Key $signerKey): Configuration
    {
        return (new static())->build($signerKey);
    }
}
