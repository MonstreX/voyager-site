<?php

namespace MonstreX\VoyagerSite;

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


    }

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadMigrationsFrom(realpath(__DIR__.'/../migrations'));

        //VSeo::generate();
        //VBreadcrumbs::add($url, $title);
        //VPage::get($alias);
        //VPage::render($alias);
        //VBlock::render('block-alias');

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

}
