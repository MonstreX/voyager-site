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

];
