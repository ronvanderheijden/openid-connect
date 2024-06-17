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
use League\OAuth2\Server\CryptTrait;
use Nyholm\Psr7\Response;
use OpenIDConnect\ClaimExtractor;
use OpenIDConnect\Claims\ClaimSet;
use OpenIDConnect\Grant\AuthCodeGrant;
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

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        $tokens_can = config('openid.passport.tokens_can', null);
        if ($tokens_can) {
            Passport\Passport::tokensCan($tokens_can);
        }

        $this->registerClaimExtractor();
    }

    public function makeAuthorizationServer(): AuthorizationServer
    {
        $cryptKey = $this->makeCryptKey('private');
        $encryptionKey = app(Encrypter::class)->getKey();

        $responseType = new IdTokenResponse(
            app(config('openid.repositories.identity')),
            app(ClaimExtractor::class),
            Configuration::forSymmetricSigner(
                app(config('openid.signer')),
                InMemory::file($cryptKey->getKeyPath()),
            ),
            app(LaravelCurrentRequestService::class),
            $encryptionKey,
        );

        return new AuthorizationServer(
            app(ClientRepository::class),
            app(AccessTokenRepository::class),
            app(config('openid.repositories.scope')),
            $cryptKey,
            $encryptionKey,
            $responseType,
        );
    }

    /**
     * Build the Auth Code grant instance.
     *
     * @return AuthCodeGrant
     */
    protected function buildAuthCodeGrant()
    {
        return new AuthCodeGrant(
            $this->app->make(Passport\Bridge\AuthCodeRepository::class),
            $this->app->make(Passport\Bridge\RefreshTokenRepository::class),
            new \DateInterval('PT10M'),
            new Response(),
            $this->app->make(LaravelCurrentRequestService::class),
        );
    }

    public function registerClaimExtractor() {
        $this->app->singleton(ClaimExtractor::class, function () {
            $customClaimSets = config('openid.custom_claim_sets');

            $claimSets = array_map(function ($claimSet, $name) {
                return new ClaimSet($name, $claimSet);
            }, $customClaimSets, array_keys($customClaimSets));

            return new ClaimExtractor(...$claimSets);
        });
    }
}
