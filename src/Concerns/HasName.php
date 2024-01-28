<?php

namespace Flexiwind\Flexiwind\Concerns;

use Closure;

trait HasName
{
    public function name(string|Closure $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->evaluate($this->name ?? null) ??
            str($this->name)->title();
    }
}
