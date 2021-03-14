<?php

declare(strict_types=1);

namespace OpenIDConnect\Claims;

interface Claimable
{
    public function getClaims(): array;
}
