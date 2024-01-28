<?php

declare(strict_types=1);

namespace Flexiwind\Flexiwind\Concerns;

trait HasPlaceholder
{
    public function getPlaceholder(): string|null
    {
        return $this->placeholder ?? str($this->label)->title()->__toString();
    }
}
