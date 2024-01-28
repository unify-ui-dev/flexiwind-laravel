<?php

declare(strict_types=1);

namespace Flexiwind\Flexiwind\Concerns;

use Closure;
use ReflectionClass;
use ReflectionMethod;

trait HasPublicMethod
{
    /**
     * Extracts all public methods from the current class instance.
     *
     * This method uses PHP's ReflectionClass to introspect the current class instance and get all its public methods.
     * For each public method, it creates a Closure from the method and stores it in an array with the method's name as the key.
     *
     * @return array An associative array where the keys are the names of the public methods and the values are Closures of the methods.
     */
    public function extractPublicMethods(): array
    {
        return collect((new ReflectionClass($this))->getMethods(ReflectionMethod::IS_PUBLIC))
            ->mapWithKeys(fn ($method) => [$method->getName() => Closure::fromCallable([$this, $method->getName()])])
            ->toArray();
    }
}
