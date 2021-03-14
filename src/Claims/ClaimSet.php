<?php

declare(strict_types=1);

namespace OpenIDConnect\Claims;

use OpenIDConnect\Claims\Traits\WithClaims;
use OpenIDConnect\Claims\Traits\WithScope;

class ClaimSet implements ClaimSetInterface
{
    use WithClaims;
    use WithScope;

    public function __construct($scope, array $claims)
    {
        $this->scope = $scope;
        $this->claims = $claims;
    }
}
