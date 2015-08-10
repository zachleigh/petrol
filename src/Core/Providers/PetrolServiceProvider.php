<?php

namespace Petrol\Core\Providers;

use Illuminate\Support\ServiceProvider;

class PetrolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            getcwd().'/vendor/zachleigh/petrol/src/Files/' => 'app/Petrol/Files/'
        ], 'petrol');

        $this->publishes([
            getcwd().'/vendor/zachleigh/petrol/src/Fillers/' => 'app/Petrol/Fillers/'
        ], 'petrol');
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFillCommand();
        $this->registerNewCommand();
    }

    /**
     * Register the Petrol fill command with artisan.
     */
    private function registerFillCommand()
    {
        $this->app->singleton('command.petrol.fill', function ($app) {
            return $app['Petrol\Core\Commands\FillCommand\ArtisanFill'];
        });

        $this->commands('command.petrol.fill');
    }

    /**
     * Register the Petrol new command with artisan.
     */
    private function registerNewCommand()
    {
        $this->app->singleton('command.petrol.new', function ($app) {
            return $app['Petrol\Core\Commands\NewCommand\ArtisanNew'];
        });

        $this->commands('command.petrol.new');
    }
}
