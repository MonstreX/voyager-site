<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

class VoyagerSiteDataRowsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $regionDataType = DataType::where('slug', 'block-regions')->firstOrFail();
        $blockDataType = DataType::where('slug', 'blocks')->firstOrFail();


        /*
         *  BLOCK REGIONS
         */
        $dataRow = $this->dataRow($regionDataType, 'id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'number',
                'display_name' => 'ID',
                'required'     => 1,
                'browse'       => 1,
                'read'         => 0,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 1,
            ])->save();
        }

        $dataRow = $this->dataRow($regionDataType, 'title');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => 'Title',
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 2,
            ])->save();
        }

        $dataRow = $this->dataRow($regionDataType, 'key');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => 'Region Key',
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 3,
            ])->save();
        }

        $dataRow = $this->dataRow($regionDataType, 'color');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'color',
                'display_name' => 'Color',
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 4,
            ])->save();
        }

        $dataRow = $this->dataRow($regionDataType, 'order');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'number',
                'display_name' => 'Order',
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 5,
            ])->save();
        }

        $dataRow = $this->dataRow($regionDataType, 'created_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => __('voyager::seeders.data_rows.created_at'),
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 6,
            ])->save();
        }

        $dataRow = $this->dataRow($regionDataType, 'updated_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => __('voyager::seeders.data_rows.updated_at'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 0,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 7,
            ])->save();
        }




        /*
         *  BLOCKS
         */
        $dataRow = $this->dataRow($blockDataType, 'id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'number',
                'display_name' => 'ID',
                'required'     => 1,
                'browse'       => 1,
                'read'         => 0,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 1,
                'details'      => [
                    'browse_align' => 'right',
                    'browse_width' => '15px',
                    'browse_font_size' => '0.8em',
                ]
            ])->save();
        }

        $dataRow = $this->dataRow($blockDataType, 'status');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'checkbox',
                'display_name' => 'Status',
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 2,
                'details'      => [
                    'browse_inline_checkbox' => true,
                    'browse_width' => '50px',
                    'on' =>'Active',
                    'off' => 'Disabled',
                    'checked' => true,
                    'display' => [
                        'width' => '12'
                    ]
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($blockDataType, 'title');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => 'Title',
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 3,
                'details'               => [
                    'url' => 'edit',
                    'display' => [
                        'width' => '6'
                    ]
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($blockDataType, 'key');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => 'Region Key',
                'required'     => 1,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 4,
                'details'               => [
                    'url' => 'edit',
                    'display' => [
                        'width' => '6'
                    ]
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($blockDataType, 'region_id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'select_dropdown',
                'display_name' => 'Region Position',
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 7,
            ])->save();
        }

        //block_belongsto_position_relationship
        $dataRow = $this->dataRow($blockDataType, 'block_belongsto_block_region_relationship');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'relationship',
                'display_name' => 'Region Position',
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 8,
                'details'      => [
                    'model' => '\\MonstreX\\VoyagerSite\\Models\\BlockRegion',
                    'table' => 'block_regions',
                    'type' => 'belongsTo',
                    'column' => 'region_id',
                    'key' => 'id',
                    'label' => 'key',
                    'pivot_table' => 'blocks',
                    'pivot' => '0',
                    'taggable' => '0',
                ],

            ])->save();
        }


        $dataRow = $this->dataRow($blockDataType, 'content');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'code_editor',
                'display_name' => 'Block Content',
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 9,
                'details'      => [
                    'language' => 'liquid',
                    'theme'    => 'monokai',
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($blockDataType, 'images');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'adv_media_files',
                'display_name' => 'Images / Files',
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 10,
                'details'      => [
                    'input_accept' => 'image\/*,.pdf,.zip,.js,.html,.doc,.xsxl',
                    'extra_fields' => [
                        'subtitle' => [
                            'type' => 'text',
                            'title' => 'Subtitle',
                        ],
                        'content' => [
                            'type' => 'codemirror',
                            'title' => 'Content',
                        ],
                        'link' => [
                            'type' => 'text',
                            'title' => 'Link',
                        ],
                    ],
                ],
            ])->save();
        }


        $dataRow = $this->dataRow($blockDataType, 'details');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'code_editor',
                'display_name' => 'Options',
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 11,
                'details'      => [
                    'language' => 'json',
                    'theme'    => 'github',
                ],
            ])->save();
        }



        $dataRow = $this->dataRow($blockDataType, 'rules');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'radio_btn',
                'display_name' => 'URL paths Rules',
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 12,
                'details'      => [
                    'default' => 0,
                    'options' => [
                        '0'    => 'Show on ALL pages, except listed',
                        '1'    => 'Show only on listed pages',
                    ],
                ],
            ])->save();
        }


        $dataRow = $this->dataRow($blockDataType, 'urls');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text_area',
                'display_name' => 'Page URLs',
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 13,
            ])->save();
        }


        $dataRow = $this->dataRow($blockDataType, 'order');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'number',
                'display_name' => 'Order',
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 14,
            ])->save();
        }

        $dataRow = $this->dataRow($blockDataType, 'created_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => __('voyager::seeders.data_rows.created_at'),
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 15,
                'details'      => [
                  'format' => '%d %B %Y',
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($blockDataType, 'updated_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => __('voyager::seeders.data_rows.updated_at'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 0,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 16,
            ])->save();
        }

    }

    /**
     * [dataRow description].
     *
     * @param [type] $type  [description]
     * @param [type] $field [description]
     *
     * @return [type] [description]
     */
    protected function dataRow($type, $field)
    {
        return DataRow::firstOrNew([
            'data_type_id' => $type->id,
            'field'        => $field,
        ]);
    }
}
