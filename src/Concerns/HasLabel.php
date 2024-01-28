<?php

namespace Flexiwind\Flexiwind\Concerns;

use Closure;

trait HasLabel
{
    
    /**
     * Gets the label of the current instance.
     *
     * If the label is a Closure, it will be evaluated. If the label is not set, it will return the title-cased name.
     *
     * @return string|Closure The label of the current instance.
     */
    public function getLabel(): string|Closure
    {
        return $this->label ?? str($this->name)->title();
    }
}
