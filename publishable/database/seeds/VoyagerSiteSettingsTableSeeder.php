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
        $details =  [
            'fields' => [
                'sec_one' => [
                    'type' => 'section',
                    'icon' => 'voyager-tools',
                    'label' => 'Main site settings',
                ],
                'title' => [
                    'label' => 'Site title',
                    'type' => 'text',
                    'value' => 'The site',
                    'class' => 'col-md-12',
                ],
                'description' => [
                    'label' => 'Site description',
                    'type' => 'text',
                    'value' => 'Description of the site',
                    'class' => 'col-md-12',
                ],
            ],
        ];

        $region = Setting::firstOrCreate([
            'title' => 'General settings',
            'key' => 'general',
            'order' => 1,
            'details' => json_encode($details, JSON_PRETTY_PRINT),
        ]);

    }
}
