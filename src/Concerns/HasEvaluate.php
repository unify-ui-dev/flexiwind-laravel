<?php

namespace Flexiwind\Flexiwind\Concerns;

use Closure;

trait HasEvaluate
{
    /**
     * Evaluates the given value.
     *
     * If the value is an instance of Closure, it will be called using the Laravel's service container.
     * Otherwise, the value itself will be returned.
     *
     * @param mixed $value The value to be evaluated.
     * @return mixed The result of the Closure call if value is a Closure, otherwise the original value.
     */
    public function evaluate(mixed $value): mixed
    {
        if ($value instanceof Closure) {
            return app()->call($value);
        }
        return $value;
    }
}
