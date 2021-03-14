<?php

declare(strict_types=1);

namespace OpenIDConnect\Claims\Traits;

trait WithClaims
{
    protected array $claims;

    public function getClaims(): array
    {
        return $this->claims;
    }

    public function setClaims(array $claims): void
    {
        $this->claims = $claims;
    }
}
