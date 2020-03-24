<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;

class VoyagerSiteMenuItemsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('name', 'admin')->firstOrFail();

        // CONTENT ROOT MENU
        $structureMenuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager-site::seeders.menu_items.content'),
            'url'     => '',
        ]);
        if (!$structureMenuItem->exists) {
            $structureMenuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-medal-rank-star',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 99,
            ])->save();
        }


        // PAGES
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager-site::seeders.menu_items.pages'),
            'url'     => '',
            'route'   => 'voyager.pages.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-file-text',
                'color'      => null,
                'parent_id'  => $structureMenuItem->id,
                'order'      => 1,
            ])->save();
        }

        // BLOCKS
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager-site::seeders.menu_items.block_and_widgets'),
            'url'     => '',
            'route'   => 'voyager.blocks.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-puzzle',
                'color'      => null,
                'parent_id'  => $structureMenuItem->id,
                'order'      => 2,
            ])->save();
        }

        // FORMS
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager-site::seeders.menu_items.forms'),
            'url'     => '',
            'route'   => 'voyager.forms.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-window-list',
                'color'      => null,
                'parent_id'  => $structureMenuItem->id,
                'order'      => 3,
            ])->save();
        }

        // SYSTEM PAGES
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager-site::seeders.menu_items.system_pages'),
            'url'     => '',
            'route'   => 'voyager.system-pages.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-file-code',
                'color'      => null,
                'parent_id'  => $structureMenuItem->id,
                'order'      => 4,
            ])->save();
        }


        // LOCALIZATIONS
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager-site::seeders.menu_items.localizations'),
            'url'     => '',
            'route'   => 'voyager.localizations.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-font',
                'color'      => null,
                'parent_id'  => $structureMenuItem->id,
                'order'      => 5,
            ])->save();
        }


        // SITE SETTINGS
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager-site::seeders.menu_items.site_settings'),
            'url'     => '',
            'route'   => 'voyager.site-settings.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-tools',
                'color'      => null,
                'parent_id'  => $structureMenuItem->id,
                'order'      => 6,
            ])->save();
        }

        // REGIONS
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager-site::seeders.menu_items.regions'),
            'url'     => '',
            'route'   => 'voyager.block-regions.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-crop',
                'color'      => null,
                'parent_id'  => $structureMenuItem->id,
                'order'      => 7,
            ])->save();
        }


        \Cache::forget('voyager_menu_'.$menu->name);

    }
}
