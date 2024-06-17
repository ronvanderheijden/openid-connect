<?php

declare(strict_types=1);

namespace OpenIDConnect;

use DateInterval;
use DateTimeImmutable;
use Defuse\Crypto\Key;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Configuration;
use League\OAuth2\Server\CryptTrait;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\ResponseTypes\BearerTokenResponse;
use OpenIDConnect\Interfaces\CurrentRequestServiceInterface;
use OpenIDConnect\Interfaces\IdentityEntityInterface;
use OpenIDConnect\Interfaces\IdentityRepositoryInterface;

class IdTokenResponse extends BearerTokenResponse
{
    use CryptTrait;

    protected IdentityRepositoryInterface $identityRepository;

    protected ClaimExtractor $claimExtractor;

    private Configuration $config;
    private ?CurrentRequestServiceInterface $currentRequestService;

    /**
     * @param string|Key|null $encryptionKey
     */
    public function __construct(
        IdentityRepositoryInterface $identityRepository,
        ClaimExtractor $claimExtractor,
        Configuration $config,
        CurrentRequestServiceInterface $currentRequestService = null,
        $encryptionKey = null,
    ) {
        $this->identityRepository = $identityRepository;
        $this->claimExtractor = $claimExtractor;
        $this->config = $config;
        $this->currentRequestService = $currentRequestService;
        $this->encryptionKey = $encryptionKey;
    }

    protected function getBuilder(
        AccessTokenEntityInterface $accessToken,
        IdentityEntityInterface $userEntity
    ): Builder {
        $dateTimeImmutableObject = new DateTimeImmutable();

        if ($this->currentRequestService) {
            $uri = $this->currentRequestService->getRequest()->getUri();
            $issuer = $uri->getScheme() . '://' . $uri->getHost() . ($uri->getPort() ? ':' . $uri->getPort() : '');
        } else {
            $issuer = 'https://' . $_SERVER['HTTP_HOST'];
        }

        return $this->config
            ->builder()
            ->permittedFor($accessToken->getClient()->getIdentifier())
            ->issuedBy($issuer)
            ->issuedAt($dateTimeImmutableObject)
            ->expiresAt($dateTimeImmutableObject->add(new DateInterval('PT1H')))
            ->relatedTo($userEntity->getIdentifier());
    }

    protected function getExtraParams(AccessTokenEntityInterface $accessToken): array
    {
        if (!$this->hasOpenIDScope(...$accessToken->getScopes())) {
            return [];
        }

        $user = $this->identityRepository->getByIdentifier(
            (string) $accessToken->getUserIdentifier(),
        );

        $builder = $this->getBuilder($accessToken, $user);

        $claims = $this->claimExtractor->extract(
            $accessToken->getScopes(),
            $user->getClaims(),
        );

        foreach ($claims as $claimName => $claimValue) {
            $builder = $builder->withClaim($claimName, $claimValue);
        }

        if ($this->currentRequestService) {
            // If the request contains a code, we look into the code to find the nonce.
            $body = $this->currentRequestService->getRequest()->getParsedBody();
            if (isset($body['code'])) {
                $authCodePayload = json_decode($this->decrypt($body['code']), true, 512, JSON_THROW_ON_ERROR);
                if (isset($authCodePayload['nonce'])) {
                    $builder = $builder->withClaim('nonce', $authCodePayload['nonce']);
                }
            }
        }

        $token = $builder->getToken(
            $this->config->signer(),
            $this->config->signingKey(),
        );

        return ['id_token' => $token->toString()];
    }

    private function hasOpenIDScope(ScopeEntityInterface ...$scopes): bool
    {
        foreach ($scopes as $scope) {
            if ($scope->getIdentifier() === 'openid') {
                return true;
            }
        }
        return false;
    }
}
