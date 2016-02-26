<?php

namespace Thorazine\Cms;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Thorazine\Cms\Commands\DatabaseBuilder;
use Config;
use View;
use App;

class CmsServiceProvider extends ServiceProvider
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

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
    }

    /**
     * All publishable configs
     *
     * @return void
     */
    private function configPublishes() {
        $this->publishes([
            __DIR__.'/config/cms.php' => config_path('cms.php'),
        ], 'cms');
    }

   
    /**
     * Register the configuration files.
     *
     * @return void
     */
    private function registerClasses() {
        $classes = [
            // include the models
            'Cms',
            //'Helpers\\CmsSetting', // A simple settings class
            //'Helpers\\CmsNaming', //
            //'Helpers\\CmsModel', //
            'Helpers\\Routing', //
            'Helpers\\CmsAuth', //
            'Helpers\\Model', //

            // include the pre made models
            'Modules\\Page\\Models\\Page',

        ];
    }

    /**
     * Register the configuration files.
     *
     * @return void
     */
    private function configMerges() {
        $this->mergeConfigFrom( __DIR__.'/config/cms.php', 'cms');
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
