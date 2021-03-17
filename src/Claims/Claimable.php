<?php

declare(strict_types=1);

namespace OpenIDConnect\Claims;

interface Claimable
{
    /**
     * @return string[]
     */
    public function getClaims(): array;
}
