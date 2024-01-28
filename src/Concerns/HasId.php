<?php

namespace Flexiwind\Flexiwind\Concerns;

use Closure;

trait HasId
{
    protected string|Closure|null $id = null;

    /**
     * Sets the ID for the current instance.
     *
     * The ID can be a string, null or a Closure. If it's a Closure, it will be evaluated when getId() is called.
     *
     * @param string|null|Closure $id The ID to be set.
     * @return static Returns the current instance.
     */
    public function id(string|Closure|null $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the ID of the current instance.
     *
     * If the ID is a Closure, it will be evaluated. If the ID is not set, it will return null.
     *
     * @return string|null The ID of the current instance.
     */
    public function getId(): ?string
    {
        return $this->evaluate($this->id);
    }
}
