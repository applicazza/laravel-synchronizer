<?php

namespace Applicazza\LaravelSynchronizer;

use Applicazza\LaravelSynchronizer\Console\Commands;
use Illuminate\Support\ServiceProvider;

class LaravelSynchronizerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/synchronizer.php' => config_path('synchronizer.php')
        ], 'config');

        $this->publishes([
            __DIR__.'/../migrations/' => database_path('migrations')
        ], 'migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/synchronizer.php', 'synchronizer');

        $this->registerCommands();

        $this->registerObjects();
    }

    protected function registerCommands()
    {
        $this->app['command.synchronizer.add'] = $this->app->share(function($app) {

            return new Commands\AddSynchronization;

        });

        $this->app['command.synchronizer.synchronize'] = $this->app->share(function($app) {

            return new Commands\ScheduleSynchronization;

        });

        $this->commands('command.synchronizer.add', 'command.synchronizer.synchronize');
    }

    protected function registerObjects()
    {
        $this->app->singleton('synchronizer', Synchronizer::class);
    }
}