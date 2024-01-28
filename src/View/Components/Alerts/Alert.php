<?php

declare(strict_types=1);

namespace Flexiwind\Flexiwind\View\Components\Alerts;

use Flexiwind\Flexiwind\Concerns\HasRender;
use Illuminate\View\Component;

class Alert extends Component
{
    use HasRender;

    protected string $view = "flexiwind::components.alerts.alert";

    public function __construct(
        public string|null $type = null,
        public string|null $description = null,
        public string|null $title = null,
        public bool        $dismissible = false,
    ) {
    }

}
