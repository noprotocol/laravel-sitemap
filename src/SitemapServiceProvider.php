<?php

namespace Noprotocol\LaravelSitemap;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Config;
use View;
use App;

class SitemapServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router, Kernel $kernel) {
        // //global middleware (runs always)
        $this->configPublishes(); // configs to be published

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/Http/routes.php'; // include the routes
        $this->registerClasses(); // register all the classes
        $this->registerCommands(); // add commands
    }

    /**
     * All publishable configs
     *
     * @return void
     */
    private function configPublishes() {
        $this->publishes([
            __DIR__.'/config/sitemap.php' => config_path('sitemap.php'),
        ], 'sitemap');
    }

   
    /**
     * Register the configuration files.
     *
     * @return void
     */
    private function registerClasses() {
        $classes = [
            // include the models
            'Sitemap',
        ];
    }

    /**
     * Register the configuration files.
     *
     * @return void
     */
    private function configMerges() {
        //$this->mergeConfigFrom( __DIR__.'/config/sitemap.php', 'sitemap');
    }

    /**
     * Register the Command services.
     *
     * @return void
     */
    private function registerCommands() {
        // $this->app['cms:db'] = $this->app->share(function($app) {
        //     return new DatabaseBuilder;
        // });

        // $this->commands('cms:db');
    }
}
