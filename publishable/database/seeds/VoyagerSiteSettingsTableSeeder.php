<?php

use Illuminate\Database\Seeder;
use MonstreX\VoyagerSite\Models\SiteSetting as Setting;

class VoyagerSiteSettingsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $refresh = config('voyager-site.seed_refresh', false);
        /*
         *  GENERAL SETTINGS
         */
        $details =  [
            'fields' => [
                'section_main' => [
                    'type' => 'section',
                    'icon' => 'voyager-tools',
                    'label' => __('voyager-site::seeders.settings.general.section_main_title'),
                ],
                'site_title' => [
                    'label' => __('voyager-site::seeders.settings.general.site_title'),
                    'type' => 'text',
                    'value' => __('voyager-site::seeders.settings.general.site_title_value'),
                    'class' => 'col-md-12',
                ],
                'site_description' => [
                    'label' => __('voyager-site::seeders.settings.general.site_description'),
                    'type' => 'text',
                    'value' => __('voyager-site::seeders.settings.general.site_description_value'),
                    'class' => 'col-md-12',
                ],
                //-
                'section_pages' => [
                    'type' => 'section',
                    'icon' => 'voyager-documentation',
                    'label' => __('voyager-site::seeders.settings.general.section_pages'),
                ],
                'site_home_page' => [
                    'label' => __('voyager-site::seeders.settings.general.site_home_page'),
                    'type' => 'number',
                    'value' => "1",
                    'class' => 'col-md-12',
                ],
                'site_403_page' => [
                    'label' => __('voyager-site::seeders.settings.general.site_403_page'),
                    'type' => 'number',
                    'value' => "1",
                    'class' => 'col-md-12',
                ],
                'site_404_page' => [
                    'label' => __('voyager-site::seeders.settings.general.site_404_page'),
                    'type' => 'number',
                    'value' => "2",
                    'class' => 'col-md-12',
                ],
                //-
                'section_system' => [
                    'type' => 'section',
                    'icon' => 'voyager-exclamation',
                    'label' => __('voyager-site::seeders.settings.general.section_system'),
                ],
                'site_app_name' => [
                    'label' => __('voyager-site::seeders.settings.general.site_app_name'),
                    'type' => 'text',
                    'value' => __('voyager-site::seeders.settings.general.site_app_name_value'),
                    'class' => 'col-md-12',
                ],
                //-
                'section_captcha' => [
                    'type' => 'section',
                    'icon' => 'voyager-puzzle',
                    'label' => __('voyager-site::seeders.settings.general.section_captcha'),
                ],
                'site_captcha_site_key' => [
                    'label' => __('voyager-site::seeders.settings.general.site_captcha_site_key'),
                    'type' => 'text',
                    'value' => '',
                    'class' => 'col-md-12',
                ],
                'site_captcha_secret_key' => [
                    'label' => __('voyager-site::seeders.settings.general.site_captcha_secret_key'),
                    'type' => 'text',
                    'value' => '',
                    'class' => 'col-md-12',
                ],
            ],
        ];

        $this->seedSetting(
            $refresh,
            'general',
            __('voyager-site::seeders.settings.general.title'),
            1,
            json_encode($details, JSON_PRETTY_PRINT)
        );


        /*
         *  MAIL
         */
        $details =  [
            'fields' => [
                'to_address' => [
                    'label' => __('voyager-site::seeders.settings.mail.to_address'),
                    'type' => 'text',
                    'value' => __('voyager-site::seeders.settings.mail.to_address_value'),
                    'class' => 'col-md-12',
                ],
                'from_name' => [
                    'label' => __('voyager-site::seeders.settings.mail.from_name'),
                    'type' => 'text',
                    'value' => __('voyager-site::seeders.settings.mail.from_name_value'),
                    'class' => 'col-md-12',
                ],
                'from_address' => [
                    'label' => __('voyager-site::seeders.settings.mail.from_address'),
                    'type' => 'text',
                    'value' => __('voyager-site::seeders.settings.mail.from_address_value'),
                    'class' => 'col-md-12',
                ],
                'section_smtp' => [
                    'type' => 'section',
                    'icon' => 'voyager-mail',
                    'label' => __('voyager-site::seeders.settings.mail.section_transport'),
                ],
                'driver' => [
                    'label' => __('voyager-site::seeders.settings.mail.driver'),
                    'type' => 'dropdown',
                    'value' => 'smtp',
                    'options' => [
                        'smtp' => 'SMTP',
                        'mailgun' => 'MAILGUN',
                        'log'   => 'LOG'
                    ],
                    'class' => 'col-md-12',
                ],
                'host' => [
                    'label' => __('voyager-site::seeders.settings.mail.host'),
                    'type' => 'text',
                    'value' => 'smtp.mailtrap.io',
                    'class' => 'col-md-12',
                ],
                'port' => [
                    'label' => __('voyager-site::seeders.settings.mail.port'),
                    'type' => 'number',
                    'value' => '2525',
                    'class' => 'col-md-12',
                ],
                'username' => [
                    'label' => __('voyager-site::seeders.settings.mail.username'),
                    'type' => 'text',
                    'value' => 'username',
                    'class' => 'col-md-12',
                ],
                'password' => [
                    'label' => __('voyager-site::seeders.settings.mail.password'),
                    'type' => 'text',
                    'value' => 'password',
                    'class' => 'col-md-12',
                ],
                'encryption' => [
                    'label' => __('voyager-site::seeders.settings.mail.encryption'),
                    'type' => 'radio',
                    'value' => 'NONE',
                    'options' => [
                        'NONE' => 'NONE',
                        'SSL' => 'SSL',
                        'TLS' => 'TLS',
                    ],
                    'class' => 'col-md-12',
                ],
                'test_mail' => [
                    'label' => __('voyager-site::seeders.settings.mail.test_mail'),
                    'type' => 'route',
                    'value' => 'send.test-mail',
                    'icon'  => 'voyager-mail',
                    'class' => 'col-md-12',
                ],

            ],
        ];

        $this->seedSetting(
            $refresh,
            'mail',
            __('voyager-site::seeders.settings.mail.title'),
            2,
            json_encode($details, JSON_PRETTY_PRINT)
        );


        /*
         *  SEO
         */
        $details =  [
            'fields' => [
                'seo_title_template' => [
                    'label' => __('voyager-site::seeders.settings.seo.seo_title_template'),
                    'type' => 'text',
                    'value' => '%site_title% | %seo_title%',
                    'class' => 'col-md-12',
                ],
                'seo_title' => [
                    'label' => __('voyager-site::seeders.settings.seo.seo_title'),
                    'type' => 'text',
                    'value' => '',
                    'class' => 'col-md-12',
                ],
                'meta_description' => [
                    'label' => __('voyager-site::seeders.settings.seo.meta_description'),
                    'type' => 'text',
                    'value' => '',
                    'class' => 'col-md-12',
                ],
                'meta_keywords' => [
                    'label' => __('voyager-site::seeders.settings.seo.meta_keywords'),
                    'type' => 'text',
                    'value' => '',
                    'class' => 'col-md-12',
                ],
                'enable_canonical' => [
                    'label' => __('voyager-site::seeders.settings.seo.enable_canonical'),
                    'type' => 'checkbox',
                    'value' => 0,
                    'on' => 'On',
                    'off' => 'Off',
                    'class' => 'col-md-12',
                ],
                'section_og' => [
                    'label' => __('voyager-site::seeders.settings.seo.section_og'),
                    'type' => 'section',
                    'icon' => 'voyager-world',
                    'class' => 'col-md-12',
                ],
                'enable_og' => [
                    'label' => __('voyager-site::seeders.settings.seo.enable_og'),
                    'type' => 'checkbox',
                    'value' => 0,
                    'on' => 'On',
                    'off' => 'Off',
                    'class' => 'col-md-12',
                ],
                'og_site_name' => [
                    'label' => __('voyager-site::seeders.settings.seo.og_site_name'),
                    'type' => 'text',
                    'value' => '',
                    'class' => 'col-md-6',
                ],
                'og_type' => [
                    'label' => __('voyager-site::seeders.settings.seo.og_type'),
                    'type' => 'dropdown',
                    'value' => 'website',
                    'class' => 'col-md-6',
                    'options' => [
                        'website' => __('voyager-site::seeders.settings.seo.og_type_website'),
                        'article' => __('voyager-site::seeders.settings.seo.og_type_article'),
                    ],
                ],
                'og_image' => [
                    'label' => __('voyager-site::seeders.settings.seo.og_image'),
                    'type' => 'media',
                    'value' => '',
                    'class' => 'col-md-12',
                ],
                'section_twitter' => [
                    'label' => __('voyager-site::seeders.settings.seo.section_twitter'),
                    'type' => 'section',
                    'icon' => 'voyager-twitter',
                    'class' => 'col-md-12',
                ],
                'enable_twitter' => [
                    'label' => __('voyager-site::seeders.settings.seo.enable_twitter'),
                    'type' => 'checkbox',
                    'value' => 0,
                    'on' => 'On',
                    'off' => 'Off',
                    'class' => 'col-md-12',
                ],
                'twitter_card' => [
                    'label' => __('voyager-site::seeders.settings.seo.twitter_card'),
                    'type' => 'dropdown',
                    'value' => 'summary_large_image',
                    'class' => 'col-md-6',
                    'options' => [
                        'summary' => __('voyager-site::seeders.settings.seo.twitter_card_summary'),
                        'summary_large_image' => __('voyager-site::seeders.settings.seo.twitter_card_summary_large'),
                    ],
                ],
                'twitter_site' => [
                    'label' => __('voyager-site::seeders.settings.seo.twitter_site'),
                    'type' => 'text',
                    'value' => '',
                    'class' => 'col-md-6',
                ],
                'twitter_image' => [
                    'label' => __('voyager-site::seeders.settings.seo.twitter_image'),
                    'type' => 'media',
                    'value' => '',
                    'class' => 'col-md-12',
                ],
            ],
        ];

        $this->seedSetting(
            $refresh,
            'seo',
            __('voyager-site::seeders.settings.seo.title'),
            3,
            json_encode($details, JSON_PRETTY_PRINT)
        );


        /*
         *  THEME
         */
        $details =  [
            'fields' => [
                'theme_logo' => [
                    'label' => __('voyager-site::seeders.settings.theme.logo'),
                    'type' => 'media',
                    'value' => '',
                    'class' => 'col-md-12',
                ],
                'theme_banner_image' => [
                    'label' => __('voyager-site::seeders.settings.theme.banner'),
                    'type' => 'media',
                    'value' => '',
                    'class' => 'col-md-12',
                ],
            ],
        ];

        $this->seedSetting(
            $refresh,
            'theme',
            __('voyager-site::seeders.settings.theme.title'),
            4,
            json_encode($details, JSON_PRETTY_PRINT)
        );

    }

    protected function seedSetting(bool $refresh, string $key, string $title, int $order, string $details): void
    {
        $payload = [
            'title' => $title,
            'order' => $order,
            'details' => $details,
        ];

        if ($refresh) {
            Setting::updateOrCreate(['key' => $key], $payload);
            return;
        }

        Setting::firstOrCreate(['key' => $key], $payload);
    }
}
