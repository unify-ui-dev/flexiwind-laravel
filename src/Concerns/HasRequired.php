<?php

declare(strict_types=1);

namespace Flexiwind\Flexiwind\Concerns;

use Closure;

trait HasRequired
{
    protected bool|Closure $isRequired = false;

    /**
     * Sets the required condition for the current instance.
     *
     * The condition can be a boolean or a Closure. If it's a Closure, it will be evaluated when isRequired() is called.
     *
     * @param bool|Closure $condition The condition to be set. Defaults to true.
     * @return static Returns the current instance.
     */
    public function required(bool|Closure $condition = true): static
    {
        $this->isRequired = $condition;

        return $this;
    }

    /**
     * Gets the required condition of the current instance.
     *
     * If the condition is a Closure, it will be evaluated. The result is then cast to boolean.
     *
     * @return bool The required condition of the current instance.
     */
    public function isRequired(): bool
    {
        return (bool)$this->evaluate($this->isRequired);
    }
}
