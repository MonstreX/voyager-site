<?php

namespace MonstreX\VoyagerSite;

use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

use MonstreX\VoyagerSite\Facades;
use MonstreX\VoyagerSite\Models\SiteSetting as Settings;
use MonstreX\VoyagerSite\Models\Localization;
use Config;
use Shortcode;
use VBlock;
use VSite;

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

        $this->registerConfigs();

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

        if (!$this->app->runningInConsole()) {
            // Override Mail settings and others
            $this->overrideConfig();
            // Load Localization strings
            $localization = app(Localization::class);
            $localization->loadLocalizations();
        }


        // Create Voyager Routes
        app(Dispatcher::class)->listen('voyager.admin.routing', function ($router) {
            $this->addRoutes($router);
        });

        $this->registerShortcodes();

        $this->registerBlades();

        $title = VSite::setting('general.site_title');

    }


    /**
     * Register the publishable files.
     */
    private function registerPublishableResources()
    {
        $this->publishes([dirname(__DIR__).'/publishable/config/voyager-site.php' => config_path('voyager-site.php')]);

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

        $router->get('/site-settings/send-test-mail', $siteController . '@sendTestMail')->name('send.test-mail');

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
            'VData' => [
                'Facade' => Facades\VoyagerData::class,
                'Alias' => 'vdata',
                'Class' =>  new VoyagerData(),
            ],
            'VPage' => [
                'Facade' => Facades\VoyagerPage::class,
                'Alias' => 'vpage',
                'Class' =>  new VoyagerPage(),
            ],
            'VBlock' => [
                'Facade' => Facades\VoyagerBlock::class,
                'Alias' => 'vblock',
                'Class' =>  new VoyagerBlock(),
            ],
        ];

        $loader = AliasLoader::getInstance();
        foreach ($aliases as $key => $alias) {
            $loader->alias($key, $alias['Facade']);
            $this->app->singleton($alias['Alias'], function () use($alias) {
                return $alias['Class'];
            });
        }

        $this->app->booting(function() {
            $loader = AliasLoader::getInstance();
            $loader->alias('Shortcode', \Webwizo\Shortcodes\Facades\Shortcode::class);
        });

    }


    public function registerConfigs()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/publishable/config/voyager-site.php', 'voyager-site'
        );
    }

    /**
     * Override Laravel Configurations
     */
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

        config()->set('app.name', $general['site_app_name']?? config('app.name'));

        config()->set('app.debug', (int) $general['site_debug_mode'] === 1? true : false);

    }

    protected function registerShortcodes()
    {
        Shortcode::register('block', 'MonstreX\VoyagerSite\Templates\CustomShortcodes@block');
        Shortcode::register('form', 'MonstreX\VoyagerSite\Templates\CustomShortcodes@form');
    }

    protected function registerBlades()
    {
        Blade::directive('renderBlock', function ($expression) {
            $expression = trim($expression, "\'\"");
            return VBlock::render($expression);
        });

        Blade::directive('renderForm', function ($expression) {
            $args = explode(',', $expression);
            $form = isset($args[0])? $args[0] : null;
            $suffix = isset($args[1])? $args[1] : null;
            return VBlock::renderForm(trim($form, "\'\" "), trim($suffix, "\'\" "));
        });

        Blade::directive('renderRegion', function ($expression) {
            $expression = trim($expression, "\'\"");
            return VBlock::renderRegion($expression);
        });
    }

}
