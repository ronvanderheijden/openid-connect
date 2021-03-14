<?php

declare(strict_types=1);

namespace OpenIDConnect\Claims\Traits;

trait WithScope
{
    protected string $scope;

    public function getScope(): string
    {
        return $this->scope;
    }
}
