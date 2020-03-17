<?php

return [
    'data_rows'  => [
        'title'            => 'Title',
        'updated_at'       => 'Updated At',
    ],
    'data_types' => [
        'settings'     => [
            'singular' => 'Settings',
            'plural'   => 'Settings',
        ],
    ],
    'menu_items' => [
    ],
    'settings'   => [
        'general' => [
            'title' => 'General settings',
            'section_main_title' => 'Base settings',
            'site_title' => 'Site title',
            'site_description' => 'Site description',

            'section_pages' => 'Special pages bindings',
            'site_home_page' => 'Home page ID',
            'site_403_page' => '404 error page ID',
            'site_404_page' => '404 error page ID',

            'section_system' => 'System settings',
            'site_root_url' => 'Site URL',
            'site_debug_mode' => 'Debug mode',
            'site_debug_mode_on' => 'Enabled',
            'site_debug_mode_off' => 'Disabled',
        ],
        'mail' => [
            'title' => 'Mail',
            'to_address' => 'Default address for site emails',
            'from_name' => 'From name',
            'from_address' => 'From address',
            'section_transport' => 'E-Mail transport',
            'driver' => 'Mail driver',
            'host' => 'Host address',
            'port' => 'Port number',
            'username' => 'User account name',
            'password' => 'Password',
        ],

        'seo' => [
            'title' => 'SEO',
            'seo_title' => 'Default page title',
            'meta_description' => 'Default page description',
            'meta_keywords' => 'Default page keywords',
        ],
    ],
];
