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
        $formDataType = DataType::where('slug', 'forms')->firstOrFail();
        $localizationDataType = DataType::where('slug', 'localizations')->firstOrFail();
        $settingsDataType = DataType::where('slug', 'site-settings')->firstOrFail();
        $pageDataType = DataType::where('slug', 'pages')->firstOrFail();
        $systemPageDataType = DataType::where('slug', 'system-pages')->firstOrFail();

        /*
         *  BLOCK REGIONS
         */
        $dataRow = $this->dataRow($regionDataType, 'id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'number',
                'display_name' => __('voyager-site::seeders.data_rows.id'),
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
                'display_name' => __('voyager-site::seeders.data_rows.title'),
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 2,
                'details'      => [
                    'url' => 'edit'
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($regionDataType, 'key');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => __('voyager-site::seeders.data_rows.region_key'),
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
                'display_name' => __('voyager-site::seeders.data_rows.color'),
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
                'display_name' => __('voyager-site::seeders.data_rows.order'),
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
                'display_name' => __('voyager-site::seeders.data_rows.created_at'),
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
                'display_name' => __('voyager-site::seeders.data_rows.updated_at'),
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
                'display_name' => __('voyager-site::seeders.data_rows.id'),
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
                'display_name' => __('voyager-site::seeders.data_rows.status'),
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
                'display_name' => __('voyager-site::seeders.data_rows.title'),
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 3,
                'details'               => [
                    'url' => 'edit',
                    'validation' => [
                        'rule' => 'required',
                        'messages' => [
                            'required' => __('voyager-site::seeders.data_rows.block_title_validate'),
                        ],
                    ],
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
                'display_name' => __('voyager-site::seeders.data_rows.block_key'),
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 4,
                'details'               => [
                    'validation' => [
                        'rule' => 'unique:blocks',
                        'messages' => [
                            'unique' => __('voyager-site::seeders.data_rows.block_slug_validate'),
                        ]
                    ],
                    'slugify' => [
                        'origin' => 'title'
                    ],
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
                'display_name' => __('voyager-site::seeders.data_rows.region_position'),
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
                'display_name' => __('voyager-site::seeders.data_rows.region_position'),
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 8,
                'details'      => [
                    'view'  => 'voyager-extension::bread.fields.block-region',
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
                'display_name' => __('voyager-site::seeders.data_rows.block_content'),
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
                'display_name' => __('voyager-site::seeders.data_rows.images_files'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 10,
                'details'      => [
                    'input_accept' => 'image/*,.pdf,.zip,.js,.html,.doc,.xsxl',
                    'extra_fields' => [
                        'subtitle' => [
                            'type' => 'text',
                            'title' => __('voyager-site::seeders.data_rows.subtitle'),
                        ],
                        'content' => [
                            'type' => 'codemirror',
                            'title' => __('voyager-site::seeders.data_rows.content'),
                        ],
                        'link' => [
                            'type' => 'text',
                            'title' => __('voyager-site::seeders.data_rows.link'),
                        ],
                    ],
                ],
            ])->save();
        }


        $dataRow = $this->dataRow($blockDataType, 'details');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'code_editor',
                'display_name' => __('voyager-site::seeders.data_rows.options'),
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
                'display_name' => __('voyager-site::seeders.data_rows.url_paths'),
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
                        '0'    => __('voyager-site::seeders.data_rows.show_on_all'),
                        '1'    => __('voyager-site::seeders.data_rows.show_only'),
                    ],
                ],
            ])->save();
        }


        $dataRow = $this->dataRow($blockDataType, 'urls');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text_area',
                'display_name' => __('voyager-site::seeders.data_rows.page_urls'),
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 13,
                'details'      => [
                    'view'  => 'voyager-extension::bread.fields.block-urls',
                ],
            ])->save();
        }


        $dataRow = $this->dataRow($blockDataType, 'order');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'number',
                'display_name' => __('voyager-site::seeders.data_rows.order'),
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
                'display_name' => __('voyager-site::seeders.data_rows.created_at'),
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
                'display_name' => __('voyager-site::seeders.data_rows.updated_at'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 0,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 16,
            ])->save();
        }


        /*
         *  FORMS
         */
        $dataRow = $this->dataRow($formDataType, 'id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'number',
                'display_name' => __('voyager-site::seeders.data_rows.id'),
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

        $dataRow = $this->dataRow($formDataType, 'status');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'checkbox',
                'display_name' => __('voyager-site::seeders.data_rows.status'),
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

        $dataRow = $this->dataRow($formDataType, 'title');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => __('voyager-site::seeders.data_rows.title'),
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 3,
                'details'      => [
                    'url' => 'edit',
                    'display' => [
                        'width' => '6'
                    ]
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($formDataType, 'key');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => __('voyager-site::seeders.data_rows.form_key'),
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 4,
                'details'      => [
                    'url' => 'edit',
                    'display' => [
                        'width' => '6'
                    ]
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($formDataType, 'content');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'code_editor',
                'display_name' => __('voyager-site::seeders.data_rows.form_content'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 5,
                'details'      => [
                    'language' => 'liquid',
                    'theme'    => 'monokai',
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($formDataType, 'details');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'code_editor',
                'display_name' => __('voyager-site::seeders.data_rows.options'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 6,
                'details'      => [
                    'language' => 'json',
                    'theme'    => 'github',
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($formDataType, 'order');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'number',
                'display_name' => __('voyager-site::seeders.data_rows.order'),
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 7,
            ])->save();
        }

        $dataRow = $this->dataRow($formDataType, 'created_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => __('voyager-site::seeders.data_rows.created_at'),
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 8,
                'details'      => [
                    'browse_width' => '150px',
                    'format' => '%d %B %Y',
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($formDataType, 'updated_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => __('voyager-site::seeders.data_rows.updated_at'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 0,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 9,
            ])->save();
        }

        /*
         * LOCALIZATIONS
         */
        $dataRow = $this->dataRow($localizationDataType, 'id');
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

        $dataRow = $this->dataRow($localizationDataType, 'key');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => __('voyager-site::seeders.data_rows.language_key'),
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 2,
                'details'      => [
                    'url' => 'edit',
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($localizationDataType, 'en');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => __('voyager-site::seeders.data_rows.english'),
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 3,
            ])->save();
        }

        $dataRow = $this->dataRow($localizationDataType, 'ru');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => __('voyager-site::seeders.data_rows.russian'),
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 4,
            ])->save();
        }


        $dataRow = $this->dataRow($localizationDataType, 'created_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => __('voyager-site::seeders.data_rows.created_at'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 5,
                'details'      => [
                    'browse_width' => '150px',
                    'format' => '%d %B %Y',
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($localizationDataType, 'updated_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => __('voyager-site::seeders.data_rows.updated_at'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 0,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 6,
            ])->save();
        }



        /*
         *  SITE SETTINGS
         */
        $dataRow = $this->dataRow($settingsDataType, 'id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'number',
                'display_name' => __('voyager-site::seeders.data_rows.id'),
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

        $dataRow = $this->dataRow($settingsDataType, 'title');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => __('voyager-site::seeders.data_rows.setting_name'),
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 2,
                'details'      => [
                    'route' => [
                        'name' => 'voyager.site-settings.edit',
                        'param_field' => 'key',
                    ],
                    'display' => [
                        'width' => '6'
                    ]
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($settingsDataType, 'key');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => __('voyager-site::seeders.data_rows.setting_group'),
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 3,
            ])->save();
        }

        $dataRow = $this->dataRow($settingsDataType, 'details');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'code_editor',
                'display_name' => __('voyager-site::seeders.data_rows.options'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 4,
                'details'      => [
                    'language' => 'json',
                    'theme'    => 'github',
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($settingsDataType, 'order');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'number',
                'display_name' => __('voyager-site::seeders.data_rows.order'),
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 5,
            ])->save();
        }

        $dataRow = $this->dataRow($settingsDataType, 'created_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => __('voyager-site::seeders.data_rows.created_at'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 6,
                'details'      => [
                    'browse_width' => '150px',
                    'format' => '%d %B %Y',
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($settingsDataType, 'updated_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => __('voyager-site::seeders.data_rows.updated_at'),
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
         *  PAGES
         */
        $dataRow = $this->dataRow($pageDataType, 'id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'number',
                'display_name' => __('voyager-site::seeders.data_rows.id'),
                'required'     => 1,
                'browse'       => 1,
                'read'         => 0,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 1,
                'details'      => [
                ]
            ])->save();
        }

        $dataRow = $this->dataRow($pageDataType, 'parent_id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'number',
                'display_name' => __('voyager-site::seeders.data_rows.parent'),
                'required'     => 1,
                'browse'       => 1,
                'read'         => 0,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 2,
                'details'      => [
                    'browse_tree' => true,
                ]
            ])->save();
        }

        $dataRow = $this->dataRow($pageDataType, 'status');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'checkbox',
                'display_name' => __('voyager-site::seeders.data_rows.status'),
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 3,
                'details'      => [
                    'browse_inline_checkbox' => true,
                    'on'      => __('voyager-site::seeders.data_rows.enabled'),
                    'off'     => __('voyager-site::seeders.data_rows.disabled'),
                    'checked' => true,
                    'display' => [
                        'width' => '12'
                    ]
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($pageDataType, 'menu');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'checkbox',
                'display_name' => __('voyager-site::seeders.data_rows.show_in_menu'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 4,
                'details'      => [
                    'browse_inline_checkbox' => true,
                    'on'      => __('voyager-site::seeders.data_rows.enabled'),
                    'off'     => __('voyager-site::seeders.data_rows.disabled'),
                    'checked' => true,
                    'display' => [
                        'width' => '12'
                    ]
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($pageDataType, 'title');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => __('voyager-site::seeders.data_rows.title'),
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 5,
                'details'               => [
                    'url' => 'edit',
                    'validation' => [
                        'rule' => 'required',
                        'messages' => [
                            'unique' => __('voyager-site::seeders.data_rows.message_required', ['field' => 'title']),
                        ]
                    ],
                    'display' => [
                        'width' => '6'
                    ]
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($pageDataType, 'slug');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => __('voyager-site::seeders.data_rows.slug'),
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 6,
                'details'               => [
                    'browse_tree_push_right' => true,
                    'validation' => [
                        'rule' => 'unique:pages',
                        'messages' => [
                            'unique' => __('voyager-site::seeders.data_rows.message_unique', ['field' => 'slug']),
                        ]
                    ],
                    'slugify' => [
                        'origin' => 'title'
                    ],
                    'display' => [
                        'width' => '6'
                    ]
                ],
            ])->save();
        }


        $dataRow = $this->dataRow($pageDataType, 'content');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'rich_text_box',
                'display_name' => __('voyager-site::seeders.data_rows.content'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 7,
            ])->save();
        }

        $dataRow = $this->dataRow($pageDataType, 'image');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'adv_image',
                'display_name' => __('voyager-site::seeders.data_rows.image'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 8,
                'details'      => [
                    'tab_title' => __('voyager-site::seeders.data_rows.tab_images'),
                ]
            ])->save();
        }

        $dataRow = $this->dataRow($pageDataType, 'images');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'adv_media_files',
                'display_name' => __('voyager-site::seeders.data_rows.images_files'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 9,
            ])->save();
        }


        $dataRow = $this->dataRow($pageDataType, 'layout');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'adv_page_layout',
                'display_name' => __('voyager-site::seeders.data_rows.layout'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 10,
                'details'      => [
                    'tab_title' => __('voyager-site::seeders.data_rows.tab_layout'),
                    'layout_fields' => [
                        'content' => __('voyager-site::seeders.data_rows.content'),
                    ],
                    'block_model' => 'MonstreX\\VoyagerSite\\Models\\Block',
                    'form_model' => 'MonstreX\\VoyagerSite\\Models\\Form',
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($pageDataType, 'seo');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'adv_fields_group',
                'display_name' => __('voyager-site::seeders.data_rows.seo_group'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 11,
                'details'      => [
                    'tab_title' => __('voyager-site::seeders.data_rows.tab_seo'),
                    'fields'   => [
                        'seo_title' => [
                            'label' => __('voyager-site::seeders.data_rows.seo_title'),
                            'type'  => 'text',
                        ],
                        'meta_description' => [
                            'label' => __('voyager-site::seeders.data_rows.meta_description'),
                            'type'  => 'text',
                        ],
                        'meta_keywords' => [
                            'label' => __('voyager-site::seeders.data_rows.meta_keywords'),
                            'type'  => 'text',
                        ],
                    ]
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($pageDataType, 'details');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'code_editor',
                'display_name' => __('voyager-site::seeders.data_rows.options'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 12,
                'details'      => [
                    'tab_title' => __('voyager-site::seeders.data_rows.tab_options'),
                    'language' => 'json',
                    'theme'    => 'github',
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($pageDataType, 'order');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'number',
                'display_name' => __('voyager-site::seeders.data_rows.order'),
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 13,
                'details'      => [
                    'tab_title' => __('voyager-site::seeders.data_rows.tab_attributes'),
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($pageDataType, 'created_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => __('voyager-site::seeders.data_rows.created_at'),
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 14,
                'details'      => [
                    'browse_align' => 'right',
                    'browse_width' => '110px',
                    'browse_font_size' => '0.9em',
                    'format' => '%Y-%m-%d',
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($pageDataType, 'updated_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => __('voyager-site::seeders.data_rows.updated_at'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 0,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 15,
            ])->save();
        }




        /*
         *  SYSTEM PAGES
         */
        $dataRow = $this->dataRow($systemPageDataType, 'id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'number',
                'display_name' => __('voyager-site::seeders.data_rows.id'),
                'required'     => 1,
                'browse'       => 1,
                'read'         => 0,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 1,
                'details'      => [
                ]
            ])->save();
        }

        $dataRow = $this->dataRow($systemPageDataType, 'status');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'checkbox',
                'display_name' => __('voyager-site::seeders.data_rows.status'),
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 3,
                'details'      => [
                    'browse_inline_checkbox' => true,
                    'checked' => true,
                    'on'      => __('voyager-site::seeders.data_rows.enabled'),
                    'off'     => __('voyager-site::seeders.data_rows.disabled'),
                    'display' => [
                        'width' => '12'
                    ]
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($systemPageDataType, 'title');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => __('voyager-site::seeders.data_rows.title'),
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 4,
                'details'               => [
                    'url' => 'edit',
                    'validation' => [
                        'rule' => 'required',
                        'messages' => [
                            'unique' => __('voyager-site::seeders.data_rows.message_required', ['field' => 'title']),
                        ]
                    ],
                    'display' => [
                        'width' => '6'
                    ]
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($systemPageDataType, 'slug');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => __('voyager-site::seeders.data_rows.slug'),
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 5,
                'details'               => [
                    'validation' => [
                        'rule' => 'unique:pages',
                        'messages' => [
                            'unique' => __('voyager-site::seeders.data_rows.message_unique', ['field' => 'slug']),
                        ]
                    ],
                    'slugify' => [
                        'origin' => 'title',
                        'forceUpdate' => true,
                    ],
                    'display' => [
                        'width' => '6'
                    ]
                ],
            ])->save();
        }


        $dataRow = $this->dataRow($systemPageDataType, 'content');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'rich_text_box',
                'display_name' => __('voyager-site::seeders.data_rows.content'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 6,
            ])->save();
        }

        $dataRow = $this->dataRow($systemPageDataType, 'image');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'adv_image',
                'display_name' => __('voyager-site::seeders.data_rows.image'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 7,
            ])->save();
        }

        $dataRow = $this->dataRow($systemPageDataType, 'images');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'adv_media_files',
                'display_name' => __('voyager-site::seeders.data_rows.images_files'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 8,
            ])->save();
        }

        $dataRow = $this->dataRow($systemPageDataType, 'details');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'code_editor',
                'display_name' => __('voyager-site::seeders.data_rows.options'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 9,
                'details'      => [
                    'language' => 'json',
                    'theme'    => 'github',
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($systemPageDataType, 'order');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'number',
                'display_name' => __('voyager-site::seeders.data_rows.order'),
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'order'        => 10,
            ])->save();
        }

        $dataRow = $this->dataRow($systemPageDataType, 'created_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => __('voyager-site::seeders.data_rows.created_at'),
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 11,
                'details'      => [
                    'browse_align' => 'right',
                    'browse_width' => '110px',
                    'browse_font_size' => '0.9em',
                    'format' => '%Y-%m-%d',
                ],
            ])->save();
        }

        $dataRow = $this->dataRow($systemPageDataType, 'updated_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => __('voyager-site::seeders.data_rows.updated_at'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 0,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 12,
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
