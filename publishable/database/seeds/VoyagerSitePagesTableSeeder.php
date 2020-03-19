<?php

use Illuminate\Database\Seeder;
use MonstreX\VoyagerSite\Models\Page;
use MonstreX\VoyagerSite\Models\SystemPage;

class VoyagerSitePagesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $record = Page::firstOrCreate([
            'title' => __('voyager-site::seeders.pages.home_title'),
            'status' => 1,
            'slug' => 'home',
        ]);

        $record = SystemPage::firstOrCreate([
            'title' => __('voyager-site::seeders.system_pages.403_title'),
            'status' => 1,
            'slug' => 'error-403',
        ]);

        $record = SystemPage::firstOrCreate([
            'title' => __('voyager-site::seeders.system_pages.404_title'),
            'status' => 1,
            'slug' => 'error-404',
        ]);

    }
}
