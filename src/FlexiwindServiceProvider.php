<?php

declare(strict_types=1);

namespace Flexiwind\Flexiwind;

use Flexiwind\Flexiwind\View\Components\Alerts\Alert;
use Flexiwind\Flexiwind\View\Components\Inputs\TextInput;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FlexiwindServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->bootConsole();
            $this->commands([]);
        }
        $this->registerComponents();
        $this->loadViewsFrom(
            __DIR__.'/../resources/views',
            'flexiwind'
        );
        $this->loadTranslationsFrom(
            __DIR__.'/../resources/lang',
            'flexiwind'
        );
    }

    protected function bootConsole(): void
    {
        $this->publishes([
            __DIR__.'/../config/flexiwind.php' => config_path('flexiwind.php'),
        ]);
    }

    protected function registerComponents(): void
    {
        $prefix = config('flexiwind.prefix');
        Blade::component($prefix.'-alert', Alert::class);
        Blade::component($prefix.'-text-input', TextInput::class);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/flexiwind.php',
            'flexiwind'
        );
    }

}
