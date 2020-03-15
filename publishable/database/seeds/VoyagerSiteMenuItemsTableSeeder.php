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


        $structureMenuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Structure',
            'url'     => '',
        ]);
        if (!$structureMenuItem->exists) {
            $structureMenuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-puzzle',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 99,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Blocks and Widgets',
            'url'     => '',
            'route'   => 'voyager.blocks.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-puzzle',
                'color'      => null,
                'parent_id'  => $structureMenuItem->id,
                'order'      => 1,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Regions',
            'url'     => '',
            'route'   => 'voyager.block-regions.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-crop',
                'color'      => null,
                'parent_id'  => $structureMenuItem->id,
                'order'      => 2,
            ])->save();
        }




        \Cache::forget('voyager_menu_'.$menu->name);


//        $menuItem = MenuItem::firstOrNew([
//            'menu_id' => $menu->id,
//            'title'   => __('voyager::seeders.menu_items.compass'),
//            'url'     => '',
//            'route'   => 'voyager.compass.index',
//        ]);
//        if (!$menuItem->exists) {
//            $menuItem->fill([
//                'target'     => '_self',
//                'icon_class' => 'voyager-compass',
//                'color'      => null,
//                'parent_id'  => $toolsMenuItem->id,
//                'order'      => 12,
//            ])->save();
//        }
//
//        $menuItem = MenuItem::firstOrNew([
//            'menu_id' => $menu->id,
//            'title'   => __('voyager::seeders.menu_items.users'),
//            'url'     => '',
//            'route'   => 'voyager.users.index',
//        ]);
//        if (!$menuItem->exists) {
//            $menuItem->fill([
//                'target'     => '_self',
//                'icon_class' => 'voyager-person',
//                'color'      => null,
//                'parent_id'  => null,
//                'order'      => 3,
//            ])->save();
//        }

    }
}
