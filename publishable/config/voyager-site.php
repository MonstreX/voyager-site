<?php

return [

    /*
     * Route for Home Page
     */
    'route_home_page' => 'home',

    /*
     * Default model table name to find records
     */
    'default_model_table' => 'pages',

    /*
     * Default slug field name
     */
    'default_slug_field' => 'slug',

    /*
     * If false will use voyager-site 404 error handler
     */
    'use_legacy_error_handler' => false,

    /*
     *  Name of the Template
     */
    'template' => 'template',

    /*
     *  Root Extendable Template
     */
    'template_master' => 'layouts.master',

    /*
     *  Main Layout Template
     */
    'template_layout' => 'layouts.main',

    /*
     *  Page Template
     */
    'template_page'   => 'pages.page',

    /*
     *  Template custom filters class
     */
    'template_filters' => null,

    /*
     *  Seed refresh flag (used by install --refresh)
     */
    'seed_refresh' => false,

    /*
    |--------------------------------------------------------------------------
    | Sitemap Configuration
    |--------------------------------------------------------------------------
    */
    'sitemap' => [
        'enabled' => false,
        'cache_ttl' => 3600,
        'include_simple_routes' => false,
        'exclude' => [
            'admin/*',
            'api/*',
            'login',
            'register',
            'password/*',
            'sanctum/*',
            'livewire/*',
            '_debugbar/*',
            '_ignition/*',
        ],
        'exclude_names' => [
            'debugbar.*',
            'ignition.*',
            'livewire.*',
            'sanctum.*',
        ],
        'routes' => [
            // Example: Pages by slug
            // 'page.show' => [
            //     'model' => \MonstreX\VoyagerSite\Models\Page::class,
            //     'param' => 'slug',
            //     'field' => 'slug',
            //     'lastmod' => 'updated_at',
            //     'changefreq' => 'weekly',
            //     'priority' => 0.8,
            // ],
            //
            // Example: Articles by slug with status filter
            // 'articles.show' => [
            //     'model' => \App\Models\Article::class,
            //     'param' => 'slug',
            //     'field' => 'slug',
            //     'status' => [
            //         'field' => 'status',
            //         'value' => [1],
            //     ],
            //     'lastmod' => 'updated_at',
            //     'changefreq' => 'weekly',
            //     'priority' => 0.6,
            // ],
        ],
        'static' => [
            '/' => ['priority' => 1.0, 'changefreq' => 'daily'],
            // '/about' => ['priority' => 0.8, 'changefreq' => 'monthly'],
            // '/contacts' => ['priority' => 0.7, 'changefreq' => 'monthly'],
        ],
        'defaults' => [
            'changefreq' => 'monthly',
            'priority' => 0.5,
        ],
    ],

];
