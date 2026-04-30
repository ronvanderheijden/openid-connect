# OpenID Connect

OpenID Connect support to the PHP League's OAuth2 Server.

**Compatible with [Laravel Passport](https://laravel.com/docs/13.x/passport)!**

## Requirements

* Requires PHP version `^8.3`.
* [lcobucci/jwt](https://github.com/lcobucci/jwt) version `^5.6`.
* [league/oauth2-server](https://github.com/thephpleague/oauth2-server) `^9.2`.
* Laravel Passport integration targets Passport `^13.0` and Laravel `^13.0`.

## Upgrade notes

This release drops support for PHP 7.4, 8.0, 8.1 and 8.2 so the Laravel Passport integration can support Laravel 13. Consumers using the Laravel integration should upgrade to Laravel 13 and Passport 13, then review Passport's own 13.x upgrade guide for application-level changes such as hashed client secrets, UUID client IDs, the `OAuthenticatable` user-model contract and the removed personal access client table.

The package now targets `league/oauth2-server` 9.x. Custom repository implementations must use the typed method signatures required by OAuth2 Server 9, and access token entities must implement `toString()`.

## Installation
```sh
composer require ronvanderheijden/openid-connect
```

## Keys

To sign and encrypt the tokens, we need a private and a public key.
```sh
mkdir -m 700 -p tmp

openssl genrsa -out tmp/private.key 2048
openssl rsa -in tmp/private.key -pubout -out tmp/public.key

chmod 600 tmp/private.key
chmod 644 tmp/public.key
```

## Example
I recommend to [read this](https://oauth2.thephpleague.com/authorization-server/auth-code-grant/) first.

To enable OpenID Connect, follow these simple steps

```php
$privateKeyPath = 'tmp/private.key';

$currentRequestService = new CurrentRequestService();
$currentRequestService->setRequest(ServerRequestFactory::fromGlobals());

// create the response_type
$responseType = new IdTokenResponse(
    new IdentityRepository(),
    new ClaimExtractor(),
    Configuration::forSymmetricSigner(
        new Sha256(),
        InMemory::file($privateKeyPath),
    ),
    $currentRequestService,
    $encryptionKey,
);

$server = new \League\OAuth2\Server\AuthorizationServer(
    $clientRepository,
    $accessTokenRepository,
    $scopeRepository,
    $privateKeyPath,
    $encryptionKey,
    // add the response_type
    $responseType,
);
```

Now when calling the `/authorize` endpoint, provide the `openid` scope to get an `id_token`.  
Provide more scopes (e.g. `openid profile email`) to receive additional claims in the `id_token`.

For a complete implementation, visit [the OAuth2 Server example](https://github.com/ronvanderheijden/openid-connect/tree/main/example).

## Nonce support

To prevent replay attacks, some clients can provide a "nonce" in the authorization request. If a client does so, the
server MUST include back a `nonce` claim in the `id_token`.

To enable this feature, when registering an AuthCodeGrant, you need to use the `\OpenIDConnect\Grant\AuthCodeGrant` 
instead of `\League\OAuth2\Server\Grant\AuthCodeGrant`.

> ![NOTE]
> If you are using Laravel, the `AuthCodeGrant` is already registered for you by the service provider.

## Laravel Passport

You can use this package with Laravel Passport in 2 simple steps.

### 1.) add the service provider
```php
# config/app.php
'providers' => [
    /*
     * Package Service Providers...
     */
    OpenIDConnect\Laravel\PassportServiceProvider::class,
],
```

### 2.) create an entity
Create an entity class in `app/Entities/` named `IdentityEntity` or `UserEntity`. This entity is used to collect the claims.
```php
# app/Entities/IdentityEntity.php
namespace App\Entities;

use League\OAuth2\Server\Entities\Traits\EntityTrait;
use OpenIDConnect\Claims\Traits\WithClaims;
use OpenIDConnect\Interfaces\IdentityEntityInterface;

class IdentityEntity implements IdentityEntityInterface
{
    use EntityTrait;
    use WithClaims;

    /**
     * The user to collect the additional information for
     */
    protected User $user;

    /**
     * The identity repository creates this entity and provides the user id
     * @param mixed $identifier
     */
    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
        $this->user = User::findOrFail($identifier);
    }

    /**
     * When building the id_token, this entity's claims are collected
     */
    public function getClaims(): array
    {
        return [
            'email' => $this->user->email,
        ];
    }
}
```

### Publishing the config
In case you want to change the default scopes, add custom claim sets or change the repositories, you can publish the openid config using:
```sh
php artisan vendor:publish --tag=openid
```

### Discovery and JWKS

The Laravel Passport integration also provides:

- a discovery endpoint at `/.well-known/openid-configuration`.
- a JWKS endpoint at `/oauth/jwks`.

Those 2 endpoints are automatically added to the Laravel routes and can be disabled from the config (using
the `openid.routes.discovery` and `openid.routes.jwks` keys).

Laravel Passport does not provide a `userinfo` endpoint by default. If you provide one, you can add it to the 
discovery document by naming the route `openid.userinfo`.

```php
Route::get('/oauth/userinfo', 'YourController@userinfo')->middleware('xxx')->name('openid.userinfo');
```

## Support
Found a bug? Got a feature request?  [Create an issue](https://github.com/ronvanderheijden/openid-connect/issues).

## License
OpenID Connect is open source and licensed under [the MIT licence](https://github.com/ronvanderheijden/openid-connect/blob/master/LICENSE.txt).
