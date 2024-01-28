<?php

namespace Flexiwind\Flexiwind\Facade;

use Flexiwind\Flexiwind\Fexiwind;
use Illuminate\Support\Facades\Facade;

class FlexiwindFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Fexiwind::class;
    }
}
