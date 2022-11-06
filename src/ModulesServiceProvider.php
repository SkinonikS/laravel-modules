<?php

namespace SkinonikS\Laravel\Modules;

use Illuminate\Support\ServiceProvider;
use SkinonikS\Laravel\Modules\Commands\ModulesCacheCommand;
use SkinonikS\Laravel\Modules\Commands\ModulesClearCacheCommand;
use SkinonikS\Laravel\Modules\Module\Activators\ActivatorManager;
use SkinonikS\Laravel\Modules\Module\Activators\ActivatorRepository;
use SkinonikS\Laravel\Modules\Module\Bootstrap\RegisterAliases;
use SkinonikS\Laravel\Modules\Module\Bootstrap\RegisterFiles;
use SkinonikS\Laravel\Modules\Module\Bootstrap\RegisterProviders;
use SkinonikS\Laravel\Modules\Module\Bootstrap\Resolver\Resolver as BootstrapperResolver;
use SkinonikS\Laravel\Modules\Module\Bootstrap\Resolver\ResolverInterface as BootstrapperResolverInterface;
use SkinonikS\Laravel\Modules\Module\Factory\ModuleFactory;
use SkinonikS\Laravel\Modules\Module\Factory\ModuleFactoryInterface;
use SkinonikS\Laravel\Modules\Module\Module;
use SkinonikS\Laravel\Modules\Repository\Repository;
use SkinonikS\Laravel\Modules\Repository\Scanner\CacheScanner;
use SkinonikS\Laravel\Modules\Repository\Scanner\Scanner;
use SkinonikS\Laravel\Modules\Repository\Scanner\ScannerInterface;

class ModulesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/modules.php', 'modules');

        $this->registerAliases();
        $this->registerServices();
        $this->registerPublishes();

        $this->commands([
            ModulesCacheCommand::class,
            ModulesClearCacheCommand::class,
        ]);

        $this->registerModules();
    }

    protected function registerModules()
    {
        collect($this->app['modules']->all())
            ->filter(fn (Module $module) => $module->isEnabled())
            ->each(fn (Module $module) => $module->boot());
    }

    protected function registerPublishes(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('modules.php'),
        ], 'config');

        // $this->publishes([
        //     __DIR__.'/src/Commands/stubs' => base_path('stubs/laravel-modules-stubs'),
        // ], 'stubs');
    }

    protected function registerServices()
    {
        $this->app->singleton('modules.activators', static function ($app) {
            return new ActivatorManager($app);
        });

        $this->app->bind('modules.activators.driver', static function ($app) {
            return $app['modules.activators']->driver();
        });

        $this->app->singleton('modules.bootstrapper', static function ($app) {
            return (new BootstrapperResolver($app['events']))
                ->registerMany([
                    new RegisterProviders($app, $app['files'], $app['config']['modules.cache.paths.module-services']),
                    new RegisterFiles(),
                    new RegisterAliases(),
                ]);
        });

        $this->app->singleton('modules.module-factory', static function ($app) {
            return new ModuleFactory();
        });

        $this->app->singleton('modules.scanner', static function ($app) {
            $baseScanner = new Scanner(
                $app['modules.module-factory'],
                $app['files'],
            );

            return new CacheScanner(
                $baseScanner,
                $app['modules.module-factory'],
                $app['files'],
                $app['config']['modules.cache.paths.manifests'],
            );
        });

        $this->app->singleton('modules', static function ($app) {
            return new Repository(
                $app['modules.scanner'],
                //$app['files'],
                $app['config']['modules.path'],
            );
        });
    }

    protected function registerAliases(): void
    {
        $this->app->alias('modules', Repository::class);

        $this->app->alias('modules.scanner', CacheScanner::class);
        $this->app->alias('modules.scanner', ScannerInterface::class);

        $this->app->alias('modules.module-factory', ModuleFactory::class);
        $this->app->alias('modules.module-factory', ModuleFactoryInterface::class);

        $this->app->alias('modules.bootstrapper', BootstrapperResolver::class);
        $this->app->alias('modules.bootstrapper', BootstrapperResolverInterface::class);

        $this->app->alias('modules.activators', ActivatorManager::class);
        $this->app->alias('modules.activators.driver', ActivatorRepository::class);
    }
}
