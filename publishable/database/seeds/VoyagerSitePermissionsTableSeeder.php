<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;

class VoyagerSitePermissionsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $keys = [
            'browse_admin',
            'browse_bread',
            'browse_database',
            'browse_media',
            'browse_compass',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => null,
            ]);
        }

        Permission::generateFor('block_regions');
        Permission::generateFor('blocks');
        Permission::generateFor('forms');
        Permission::generateFor('localizations');
        Permission::generateFor('site_settings');
        Permission::generateFor('pages');
        Permission::generateFor('system_pages');
    }
}
