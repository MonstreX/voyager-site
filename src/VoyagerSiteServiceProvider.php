<?php

namespace MonstreX\VoyagerSite;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Validator;
use GuzzleHttp\Client;
use Route;

use MonstreX\VoyagerSite\Facades;
use MonstreX\VoyagerSite\Models\SiteSetting as Settings;
use MonstreX\VoyagerSite\Models\Localization;
use MonstreX\VoyagerSite\Services\VoyagerSite;
use MonstreX\VoyagerSite\Services\VoyagerBlock;
use MonstreX\VoyagerSite\Services\VoyagerPage;
use MonstreX\VoyagerSite\Services\VoyagerData;


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

        $this->loadRoutesFrom(__DIR__.'/Routes/routes.php');

        $this->registerShortcodes();

        $this->registerBlades();

        Validator::extend('recaptcha','\MonstreX\VoyagerSite\Validators\ReCaptcha@validate');

    }

    /**
     * Register the publishable files.
     */
    private function registerPublishableResources()
    {
        $this->publishes([dirname(__DIR__).'/publishable/config/voyager-site.php' => config_path('voyager-site.php')], 'config');

        $publishablePath = dirname(__DIR__).'/publishable';

        $publishable = [
            'assets' => [
                "{$publishablePath}/assets/" => public_path('vendor/voyager-site'),
            ],
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
        $this->commands([
            Commands\InstallCommand::class,
            Commands\UninstallCommand::class,
        ]);
    }

    /*
     *  Add Routes
     */
    public function addRoutes($router, $site_controller){

        $router->get('/site-settings/{key}/edit', $site_controller . '@settingsEdit')->name('site-settings.edit')->where('key', '[A-Za-z]+');;

        $router->post('/site-settings/{key}/update', $site_controller . '@settingsUpdate')->name('site-settings.save');

        $router->get('/site-settings/send-test-mail', $site_controller . '@sendTestMail')->name('send.test-mail');

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
        try {
            if (!Schema::hasTable('site_settings')) {
                return;
            }

            $settings = app(Settings::class);
            $mail = $settings->getSettingsGroup('mail');
            if (empty($mail)) {
                return;
            }

            $mail = collect($mail)
                ->map(function ($value) {
                    return is_string($value) ? trim($value) : $value;
                })
                ->all();

            $driver = strtolower((string) ($mail['driver'] ?? config('mail.default', 'smtp')));
            $encryptionSource = strtoupper((string) ($mail['encryption'] ?? config('mail.mailers.smtp.encryption')));
            $encryption = $encryptionSource === 'NONE' ? null : strtolower($encryptionSource);

            config([
                'mail.default' => $driver,
                'mail.from.address' => $mail['from_address'] ?? config('mail.from.address'),
                'mail.from.name' => $mail['from_name'] ?? config('mail.from.name'),
            ]);

            if ($driver === 'smtp') {
                config([
                    'mail.mailers.smtp' => array_replace_recursive(config('mail.mailers.smtp', []), [
                        'transport' => 'smtp',
                        'host' => $mail['host'] ?? config('mail.mailers.smtp.host'),
                        'port' => isset($mail['port']) ? (int) $mail['port'] : config('mail.mailers.smtp.port'),
                        'username' => $mail['username'] ?? config('mail.mailers.smtp.username'),
                        'password' => $mail['password'] ?? config('mail.mailers.smtp.password'),
                        'encryption' => $encryption,
                        'timeout' => 10,
                    ]),
                ]);
            }

            $general = $settings->getSettingsGroup('general');
            if (!empty($general['site_app_name'])) {
                config(['app.name' => $general['site_app_name']]);
            }
        } catch (\Exception $e) {
            // Ignore configuration errors during early boot.
        }

    }

    protected function registerShortcodes()
    {
        Shortcode::register('block', 'MonstreX\VoyagerSite\Templates\CustomShortcodes@block');
        Shortcode::register('form', 'MonstreX\VoyagerSite\Templates\CustomShortcodes@form');
        Shortcode::register('div', 'MonstreX\VoyagerSite\Templates\CustomShortcodes@div');
        Shortcode::register('image', 'MonstreX\VoyagerSite\Templates\CustomShortcodes@image');
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
            $subject = isset($args[1])? $args[1] : null;
            $suffix = isset($args[2])? $args[2] : null;
            return VBlock::renderForm(trim($form, "\'\" "), $subject, trim($suffix, "\'\" "));
        });

        Blade::directive('renderRegion', function ($expression) {
            $expression = trim($expression, "\'\"");
            return VBlock::renderRegion($expression);
        });
    }
}
