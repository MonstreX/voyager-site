<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataType;

class VoyagerSiteDataTypesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {

        // BLOCK REGIONS
        $dataType = $this->dataType('slug', 'block-regions');
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => 'block_regions',
                'display_name_singular' => __('voyager-site::seeders.data_types.block_regions.singular'),
                'display_name_plural'   => __('voyager-site::seeders.data_types.block_regions.plural'),
                'icon'                  => 'voyager-crop',
                'model_name'            => 'MonstreX\\VoyagerSite\\Models\\BlockRegion',
                'policy_name'           => '',
                'controller'            => '',
                'generate_permissions'  => 1,
                'description'           => '',
                'details'               => [
                    'order_column' => 'order',
                    'order_display_column' =>'title',
                    'order_direction' => 'asc',
                    'default_search_key' =>null,
                    'scope' => null,
                ],
            ])->save();
        }

        // BLOCKS
        $dataType = $this->dataType('slug', 'blocks');
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => 'blocks',
                'display_name_singular' => __('voyager-site::seeders.data_types.blocks.singular'),
                'display_name_plural'   => __('voyager-site::seeders.data_types.blocks.plural'),
                'icon'                  => 'voyager-puzzle',
                'model_name'            => 'MonstreX\\VoyagerSite\\Models\\Block',
                'policy_name'           => '',
                'controller'            => '',
                'generate_permissions'  => 1,
                'description'           => '',
                'details'               => [
                    'order_column' => 'order',
                    'order_display_column' =>'title',
                    'order_direction' => 'asc',
                    'default_search_key' =>null,
                    'scope' => null,
                ],
            ])->save();
        }

        // FORMS
        $dataType = $this->dataType('slug', 'forms');
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => 'forms',
                'display_name_singular' => __('voyager-site::seeders.data_types.forms.singular'),
                'display_name_plural'   => __('voyager-site::seeders.data_types.forms.plural'),
                'icon'                  => 'voyager-window-list',
                'model_name'            => 'MonstreX\\VoyagerSite\\Models\\Form',
                'policy_name'           => '',
                'controller'            => '',
                'generate_permissions'  => 1,
                'description'           => '',
                'details'               => [
                    'order_column' => 'order',
                    'order_display_column' =>'title',
                    'order_direction' => 'asc',
                    'default_search_key' =>null,
                    'scope' => null,
                ],
            ])->save();
        }

        // LOCALIZATIONS
        $dataType = $this->dataType('slug', 'localizations');
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => 'localizations',
                'display_name_singular' => __('voyager-site::seeders.data_types.localizations.singular'),
                'display_name_plural'   => __('voyager-site::seeders.data_types.localizations.plural'),
                'icon'                  => 'voyager-font',
                'model_name'            => 'MonstreX\\VoyagerSite\\Models\\Localization',
                'policy_name'           => '',
                'controller'            => '',
                'generate_permissions'  => 1,
                'description'           => '',
                'details'               => [
                    'order_column' => null,
                    'order_display_column' => null,
                    'order_direction' => null,
                    'default_search_key' =>null,
                    'scope' => null,
                ],
            ])->save();
        }

        // SETTINGS
        $dataType = $this->dataType('slug', 'site-settings');
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => 'site_settings',
                'display_name_singular' => __('voyager-site::seeders.data_types.site_settings.singular'),
                'display_name_plural'   => __('voyager-site::seeders.data_types.site_settings.plural'),
                'icon'                  => 'voyager-tools',
                'model_name'            => 'MonstreX\\VoyagerSite\\Models\\SiteSetting',
                'policy_name'           => '',
                'controller'            => '',
                'generate_permissions'  => 1,
                'description'           => '',
                'details'               => [
                    'order_column' => 'order',
                    'order_display_column' =>'title',
                    'order_direction' => 'asc',
                    'default_search_key' =>null,
                    'scope' => null,
                ],
            ])->save();
        }

        // PAGES
        $dataType = $this->dataType('slug', 'pages');
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => 'pages',
                'display_name_singular' => __('voyager-site::seeders.data_types.pages.singular'),
                'display_name_plural'   => __('voyager-site::seeders.data_types.pages.plural'),
                'icon'                  => 'voyager-file-text',
                'model_name'            => 'MonstreX\\VoyagerSite\\Models\\Page',
                'policy_name'           => '',
                'controller'            => '',
                'generate_permissions'  => 1,
                'description'           => '',
                'details'               => [
                    'order_column' => null,
                    'order_display_column' => null,
                    'order_direction' => null,
                    'default_search_key' =>null,
                    'scope' => null,
                ],
            ])->save();
        }

        // SYSTEM PAGES
        $dataType = $this->dataType('slug', 'system-pages');
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => 'system_pages',
                'display_name_singular' => __('voyager-site::seeders.data_types.system_pages.singular'),
                'display_name_plural'   => __('voyager-site::seeders.data_types.system_pages.plural'),
                'icon'                  => 'voyager-file-code',
                'model_name'            => 'MonstreX\\VoyagerSite\\Models\\SystemPage',
                'policy_name'           => '',
                'controller'            => '',
                'generate_permissions'  => 1,
                'description'           => '',
                'details'               => [
                    'order_column' => null,
                    'order_display_column' => null,
                    'order_direction' => null,
                    'default_search_key' =>null,
                    'scope' => null,
                ],
            ])->save();
        }


    }

    /**
     * [dataType description].
     *
     * @param [type] $field [description]
     * @param [type] $for   [description]
     *
     * @return [type] [description]
     */
    protected function dataType($field, $for)
    {
        return DataType::firstOrNew([$field => $for]);
    }
}
