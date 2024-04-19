<?php

declare(strict_types=1);

namespace OpenIDConnect\Laravel;

use Illuminate\Encryption\Encrypter;
use Laravel\Passport;
use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\ClientRepository;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use League\OAuth2\Server\AuthorizationServer;
use OpenIDConnect\ClaimExtractor;
use OpenIDConnect\Claims\ClaimSet;
use OpenIDConnect\IdTokenResponse;

class PassportServiceProvider extends Passport\PassportServiceProvider
{
    public function register()
    {
        parent::register();

        $this->mergeConfigFrom(
            __DIR__ . '/config/openid.php',
            'openid'
        );
    }

    public function boot()
    {
        parent::boot();

        $this->publishes([
            __DIR__ . '/config/openid.php' => $this->app->configPath('openid.php'),
        ], ['openid', 'openid-config']);

        $openIdScopes = collect(config('openid.passport.tokens_can'));
        $previousScopes = collect(Passport\Passport::scopes())->mapWithKeys(function (Passport\Scope $scope) {
            return [$scope->id => $scope->description];
        });
        Passport\Passport::tokensCan($previousScopes->merge($openIdScopes)->toArray());
    }

    public function makeAuthorizationServer(): AuthorizationServer
    {
        $cryptKey = $this->makeCryptKey('private');

        $customClaimSets = config('openid.custom_claim_sets');

        $claimSets = array_map(function ($claimSet, $name) {
            return new ClaimSet($name, $claimSet);
        }, $customClaimSets, array_keys($customClaimSets));

        $responseType = new IdTokenResponse(
            app(config('openid.repositories.identity')),
            new ClaimExtractor(...$claimSets),
            Configuration::forSymmetricSigner(
                app(config('openid.signer')),
                InMemory::file($cryptKey->getKeyPath()),
            ),
            app('request')->getSchemeAndHttpHost(),
        );

        return new AuthorizationServer(
            app(ClientRepository::class),
            app(AccessTokenRepository::class),
            app(config('openid.repositories.scope')),
            $cryptKey,
            app(Encrypter::class)->getKey(),
            $responseType,
        );
    }
}
