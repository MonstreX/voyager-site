<?php

namespace MonstreX\VoyagerSite;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use TCG\Voyager\Facades\Voyager;

class VoyagerSiteServiceProvider extends ServiceProvider
{

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {

        if ($this->app->runningInConsole()) {
            $this->registerPublishableResources();
            $this->registerConsoleCommands();
        }

        $this->loadHelpers();


    }

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadMigrationsFrom(realpath(__DIR__.'/../migrations'));

        // Bind Views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'voyager-site');

        //VSeo::generate();
        //VBreadcrumbs::add($url, $title);
        //VPage::get($alias);
        //VPage::render($alias);
        //VBlock::render('block-alias');

        // Create Voyager Routes
        app(Dispatcher::class)->listen('voyager.admin.routing', function ($router) {
            $this->addRoutes($router);
        });

    }


    /**
     * Register the publishable files.
     */
    private function registerPublishableResources()
    {
        $publishablePath = dirname(__DIR__).'/publishable';

        $publishable = [
            'seeds' => [
                "{$publishablePath}/database/seeds/" => database_path('seeds'),
            ],
        ];

        foreach ($publishable as $group => $paths) {
            $this->publishes($paths, $group);
        }
    }


    /**
     * Register the commands accessible from the Console.
     */
    private function registerConsoleCommands()
    {
        $this->commands(Commands\InstallCommand::class);
    }

    /*
     *  Add Routes
     */
    public function addRoutes($router){

        $siteController = '\MonstreX\VoyagerSite\Http\Controllers\VoyagerSiteController';

        $router->get('/site-settings/{key}/edit', $siteController . '@settingsEdit')->name('site-settings.edit')->where('key', '[A-Za-z]+');;

        $router->post('/site-settings/{key}/update', $siteController . '@settingsUpdate')->name('site-settings.save');

    }


    /**
     * Load helpers.
     */
    protected function loadHelpers()
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }


}
