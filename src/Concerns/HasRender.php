<?php

namespace Flexiwind\Flexiwind\Concerns;

use Illuminate\Contracts\View\View;

trait HasRender
{
    public function render(): View
    {
        return view($this->view);
    }
}
