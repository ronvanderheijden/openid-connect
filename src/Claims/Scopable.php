<?php

declare(strict_types=1);

namespace OpenIDConnect\Claims;

interface Scopable
{
    public function getScope(): string;
}
