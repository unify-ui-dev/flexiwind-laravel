<?php

declare(strict_types=1);

namespace Laravel\Jetstream\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class InstallCommand extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flexiwind:install {stack : The development stack that should be installed (flexilla,alpinejs)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Flexiwind components and resources';

    /**
     * Delete the "node_modules" directory and remove the associated lock files.
     *
     * @return void
     */
    protected static function flushNodeModules(): void
    {
        tap(new Filesystem(), function ($files): void {
            $files->deleteDirectory(base_path('node_modules'));

            $files->delete(base_path('pnpm-lock.yaml'));
            $files->delete(base_path('yarn.lock'));
            $files->delete(base_path('package-lock.json'));
        });
    }

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle()
    {
        if (!in_array($this->argument('stack'), ['flexilla', 'alpine js'])) {
            $this->components->error('Invalid stack. Supported stacks are [flexilla] and [alpine js].');

            return 1;
        }

        // Publish...
        $this->callSilent('vendor:publish', ['--tag' => 'jetstream-config', '--force' => true]);

        // Install Stack...
        if ('flexilla' === $this->argument('stack')) {
            if (!$this->installFlexillaStack()) {
                return 1;
            }
        } elseif ('alpine js' === $this->argument('stack')) {
            if (!$this->installAlpinejsStack()) {
                return 1;
            }
        }
    }

    /**
     * Install the Livewire stack into the application.
     *
     * @return bool
     */
    protected function installFlexillaStack()
    {
        // Install Livewire...
        if (!$this->requireComposerPackages('flexilla')) {
            return false;
        }
        // NPM Packages...
        $this->updateNodePackages(
            fn($packages) => [
                    '@tailwindcss/forms' => '^0.5.2',
                    '@tailwindcss/typography' => '^0.5.0',
                    'autoprefixer' => '^10.4.7',
                    'postcss' => '^8.4.14',
                    'tailwindcss' => '^3.1.0',
                ] + $packages
        );

        // Tailwind Configuration...
        copy(__DIR__ . '/../../stubs/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__ . '/../../stubs/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__ . '/../../stubs/vite.config.js', base_path('vite.config.js'));

        // Directories...
        (new Filesystem())->ensureDirectoryExists(resource_path('css'));

        // Assets...
        copy(__DIR__ . '/../../stubs/app.css', resource_path('css/app.css'));

        if (file_exists(base_path('pnpm-lock.yaml'))) {
            $this->runCommands(['pnpm install', 'pnpm run build']);
        } elseif (file_exists(base_path('yarn.lock'))) {
            $this->runCommands(['yarn install', 'yarn run build']);
        } else {
            $this->runCommands(['npm install', 'npm run build']);
        }

        $this->line('');
        $this->components->info('Flexilla scaffolding installed successfully.');

        return true;
    }

    /**
     * Installs the given Composer Packages into the application.
     *
     * @param mixed $packages
     * @return bool
     */
    protected function requireComposerPackages($packages)
    {
        $composer = $this->option('npm');

        if ('global' !== $composer) {
            $command = [$this->npmBinary(), $composer, 'install'];
        }

        $command = array_merge(
            $command ?? ['npm', 'install'],
            is_array($packages) ? $packages : func_get_args()
        );

        return !(new Process($command, base_path()))
            ->setTimeout(null)
            ->run(function ($type, $output): void {
                $this->output->write($output);
            });
    }

    /**
     * Get the path to the appropriate PHP binary.
     *
     * @return string
     */
    protected function npmBinary()
    {
        return (new PhpExecutableFinder())->find(false) ?: 'npm';
    }

    /**
     * Update the "package.json" file.
     *
     * @param callable $callback
     * @param bool $dev
     * @return void
     */
    protected static function updateNodePackages(callable $callback, $dev = true): void
    {
        if (!file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }

    /**
     * Run the given commands.
     *
     * @param array $commands
     * @return void
     */
    protected function runCommands($commands): void
    {
        $process = Process::fromShellCommandline(implode(' && ', $commands), null, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            } catch (RuntimeException $e) {
                $this->output->writeln('  <bg=yellow;fg=black> WARN </> ' . $e->getMessage() . PHP_EOL);
            }
        }

        $process->run(function ($type, $line): void {
            $this->output->write('    ' . $line);
        });
    }

    /**
     * Install the Inertia stack into the application.
     *
     * @return bool
     */
    protected function installAlpinejsStack()
    {
        // Install Inertia...
        if (!$this->requireComposerPackages('alpinejs')) {
            return false;
        }

        // Install NPM packages...
        $this->updateNodePackages(function ($packages) {
            return [
                    '@tailwindcss/forms' => '^0.5.2',
                    '@tailwindcss/typography' => '^0.5.2',
                    'autoprefixer' => '^10.4.7',
                    'postcss' => '^8.4.14',
                    'tailwindcss' => '^3.1.0',
                ] + $packages;
        });


        // Tailwind Configuration...
        copy(__DIR__ . '/../../stubs/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__ . '/../../stubs/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__ . '/../../stubs/vite.config.js', base_path('vite.config.js'));

        (new Filesystem())->ensureDirectoryExists(resource_path('css'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js'));

        // Assets...
        copy(__DIR__ . '/../../stubs/app.css', resource_path('css/app.css'));
        copy(__DIR__ . '/../../stubs/js/app.js', resource_path('js/app.js'));

        if (file_exists(base_path('pnpm-lock.yaml'))) {
            $this->runCommands(['pnpm install', 'pnpm run build']);
        } elseif (file_exists(base_path('yarn.lock'))) {
            $this->runCommands(['yarn install', 'yarn run build']);
        } else {
            $this->runCommands(['npm install', 'npm run build']);
        }

        $this->line('');
        $this->components->info('Inertia scaffolding installed successfully.');

        return true;
    }

    /**
     * Replace a given string within a given file.
     *
     * @param string $search
     * @param string $replace
     * @param string $path
     * @return void
     */
    protected function replaceInFile($search, $replace, $path): void
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }

    /**
     * Determine if the given Composer package is installed.
     *
     * @param string $package
     * @return bool
     */
    protected function hasComposerPackage($package)
    {
        $packages = json_decode(file_get_contents(base_path('composer.json')), true);

        return array_key_exists($package, $packages['require'] ?? [])
            || array_key_exists($package, $packages['require-dev'] ?? []);
    }

    /**
     * Removes the given Composer Packages as "dev" dependencies.
     *
     * @param mixed $packages
     * @return bool
     */
    protected function removeComposerDevPackages($packages)
    {
        $composer = $this->option('composer');

        if ('global' !== $composer) {
            $command = [$this->npmBinary(), $composer, 'remove', '--dev'];
        }

        $command = array_merge(
            $command ?? ['composer', 'remove', '--dev'],
            is_array($packages) ? $packages : func_get_args()
        );

        return 0 === (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
                ->setTimeout(null)
                ->run(function ($type, $output): void {
                    $this->output->write($output);
                });
    }

    /**
     * Install the given Composer Packages as "dev" dependencies.
     *
     * @param mixed $packages
     * @return bool
     */
    protected function requireComposerDevPackages($packages)
    {
        $composer = $this->option('composer');

        if ('global' !== $composer) {
            $command = [$this->npmBinary(), $composer, 'require', '--dev'];
        }

        $command = array_merge(
            $command ?? ['composer', 'require', '--dev'],
            is_array($packages) ? $packages : func_get_args()
        );

        return 0 === (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
                ->setTimeout(null)
                ->run(function ($type, $output): void {
                    $this->output->write($output);
                });
    }


    /**
     * Remove Tailwind dark classes from the given files.
     *
     * @param Finder $finder
     * @return void
     */
    protected function removeDarkClasses(Finder $finder): void
    {
        foreach ($finder as $file) {
            file_put_contents($file->getPathname(), preg_replace('/\sdark:[^\s"\']+/', '', $file->getContents()));
        }
    }
}
