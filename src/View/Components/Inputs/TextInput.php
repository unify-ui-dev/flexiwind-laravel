<?php

declare(strict_types=1);

namespace Flexiwind\Flexiwind\View\Components\Inputs;

use Flexiwind\Flexiwind\Concerns\HasLabel;
use Flexiwind\Flexiwind\Concerns\HasPlaceholder;
use Flexiwind\Flexiwind\Concerns\HasRender;
use Illuminate\View\Component;

class TextInput extends Component
{
    use HasLabel;
    use HasPlaceholder;
    use HasRender;

    protected string $view = "flexiwind::components.inputs.text-input";

    public function __construct(
        public string|null $label = null,
        public string|null $name = null,
        public string|null $value = null,
        public string|null $placeholder = null,
        public string|null $type = null,
    ) {
    }

    public function getType(): string
    {
        return $this->type ?? 'text';
    }
}
