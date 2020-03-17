<?php

namespace MonstreX\VoyagerSite;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use MonstreX\VoyagerSite\Facades;
use MonstreX\VoyagerSite\Models\SiteSetting as Settings;
use MonstreX\VoyagerSite\Models\Localization;
use Config;

class VoyagerSiteServiceProvider extends ServiceProvider
{

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {

        $this->registerAliases();

        $this->loadHelpers();

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

        // Bind Views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'voyager-site');

        $this->loadTranslationsFrom(realpath(__DIR__.'/../publishable/lang'), 'voyager-site');

        // Override Mail settings and others
        $this->overrideConfig();

        // Load Localization strings
        $localization = app(Localization::class);
        $localization->loadLocalizations();

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

    /**
     * Register Aliases
     */
    protected function registerAliases()
    {
        $aliases = [
            'VSite' => [
                'Facade' => Facades\VoyagerSite::class,
                'Alias' => 'vsite',
                'Class' =>  new VoyagerSite(),
            ],
            'VPage' => [
                'Facade' => Facades\VoyagerPage::class,
                'Alias' => 'vpage',
                'Class' =>  new VoyagerPage(),
            ],
        ];

        $loader = AliasLoader::getInstance();
        foreach ($aliases as $key => $alias) {
            $loader->alias($key, $alias['Facade']);
            $this->app->singleton($alias['Alias'], function () use($alias) {
                return $alias['Class'];
            });
        }

    }

    protected function overrideConfig()
    {
        $settings = app(Settings::class);
        $mail = $settings->getSettingsGroup('mail');

        config()->set('mail', [
            'driver'     => $mail['driver']? :'smtp',
            'host'       => $mail['host']? :'smtp.mailgun.org',
            'port'       => $mail['port']? : 587,
            'from'       => [
                'address' => $mail['from_address']? :'hello@example.com',
                'name' => $mail['from_name']? :'Example',
            ],
            'encryption' => $mail['encryption']? ($mail['encryption'] === 'NONE'? null : strtolower($mail['encryption']) ) :'tsl',
            'username'   => $mail['username']? :config('mail.username'),
            'password'   => $mail['password']? :config('mail.password'),
            ]);

        $general = $settings->getSettingsGroup('general');

        config()->set('app.name', $general['site_title']?? config('app.name'));

        config()->set('app.debug', (int) $general['site_debug_mode'] === 1? true : false);

    }


}
