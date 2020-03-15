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

        $dataType = $this->dataType('slug', 'block-regions');
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => 'block_regions',
                'display_name_singular' => 'Region',
                'display_name_plural'   => 'Regions',
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

        $dataType = $this->dataType('slug', 'blocks');
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => 'blocks',
                'display_name_singular' => 'Block',
                'display_name_plural'   => 'Blocks',
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
