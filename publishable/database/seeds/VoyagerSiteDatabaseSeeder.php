<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Traits\Seedable;

class VoyagerSiteDatabaseSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath = __DIR__.'/';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seed('VoyagerSiteBlockRegionsTableSeeder');
        $this->seed('VoyagerSiteMenuItemsTableSeeder');

        $this->seed('VoyagerSiteDataTypesTableSeeder');
        $this->seed('VoyagerSiteDataRowsTableSeeder');

        $this->seed('VoyagerSitePermissionsTableSeeder');
        $this->seed('VoyagerSitePermissionRoleTableSeeder');

        $this->seed('VoyagerSiteSettingsTableSeeder');

        $this->seed('VoyagerSitePagesTableSeeder');
    }
}
