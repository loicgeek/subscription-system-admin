<?php

namespace NtechServices\SubscriptionSystemAdmin;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Route;
use Livewire\Features\SupportTesting\Testable;
use NtechServices\SubscriptionSystemAdmin\Commands\SubscriptionSystemAdminCommand;
use NtechServices\SubscriptionSystemAdmin\Testing\TestsSubscriptionSystemAdmin;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SubscriptionSystemAdminServiceProvider extends PackageServiceProvider
{
    public static string $name = 'subscription-system-admin';

    public static string $viewNamespace = 'subscription-system-admin';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            // ->hasRoutes(['web', 'api']) // Add this line for route registration
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('loicgeek/subscription-system-admin');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
         // Publish config file
         $this->publishes([
            __DIR__ . '/../config/subscription-system-admin.php' => config_path('subscription-system-admin.php'),
        ], 'subscription-system-admin-config');
         // Publish language files
       // Load package translations
    $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'subscription-system-admin');

    // Change this path to use Laravel's standard vendor path
    $this->publishes([
        __DIR__ . '/../resources/lang' => $this->app->langPath('vendor/subscription-system-admin'),
    ], 'subscription-system-admin-lang');


        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/subscription-system-admin/{$file->getFilename()}"),
                ], 'subscription-system-admin-stubs');
            }
        }
        if (config('subscription-system-admin.enable_api_routes', true)) {
            $this->registerApiRoutes();
        }
    
        if (config('subscription-system-admin.enable_web_routes', false)) {
            $this->registerWebRoutes();
        }

        // Testing
        Testable::mixin(new TestsSubscriptionSystemAdmin);
    }

    protected function registerApiRoutes(): void
{
    Route::group([
        'prefix' => config('subscription-system-admin.api_prefix', 'api/subscription'),
        'middleware' => config('subscription-system-admin.api_middleware', ['api']),
    ], function () {
        require __DIR__ . '/../routes/api.php';
    });
}

protected function registerWebRoutes(): void
{
    Route::group([
        'prefix' => config('subscription-system-admin.web_prefix', 'subscription'),
        'middleware' => config('subscription-system-admin.web_middleware', ['web', 'auth']),
    ], function () {
        require __DIR__ . '/../routes/web.php';
    });
}

    protected function getAssetPackageName(): ?string
    {
        return 'loicgeek/subscription-system-admin';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('subscription-system-admin', __DIR__ . '/../resources/dist/components/subscription-system-admin.js'),
            // Css::make('subscription-system-admin-styles', __DIR__ . '/../resources/dist/subscription-system-admin.css'),
            // Js::make('subscription-system-admin-scripts', __DIR__ . '/../resources/dist/subscription-system-admin.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            // SubscriptionSystemAdminCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [
           
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            // 'create_subscription-system-admin_table',
        ];
    }
}
