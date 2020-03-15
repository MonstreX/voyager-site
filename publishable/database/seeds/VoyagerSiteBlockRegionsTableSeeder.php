<?php

use Illuminate\Database\Seeder;
use MonstreX\VoyagerSite\Models\BlockRegion as Region;

class VoyagerSiteBlockRegionsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $region = Region::firstOrCreate([
            'title' => 'A region before of main content',
            'key' => 'content-before',
            'order' => 1,
            'color' => '#2f8516',
        ]);

        $region = Region::firstOrCreate([
            'title' => 'A region of main content',
            'key' => 'content',
            'order' => 2,
            'color' => '#2f8516',
        ]);

        $region = Region::firstOrCreate([
            'title' => 'A region after of main content',
            'key' => 'content-after',
            'order' => 3,
            'color' => '#4bc2a2',
        ]);

        $region = Region::firstOrCreate([
            'title' => 'A region without position on the page',
            'key' => 'no-position',
            'order' => 4,
            'color' => '#c7c7c7',
        ]);

        $region = Region::firstOrCreate([
            'title' => 'A sidebar region',
            'key' => 'sidebar',
            'order' => 5,
            'color' => '#e20a0f',
        ]);
    }
}
