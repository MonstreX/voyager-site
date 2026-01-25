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
        $refresh = config('voyager-site.seed_refresh', false);
        $menu = Menu::where('name', 'admin')->firstOrFail();

        // CONTENT ROOT MENU
        $contentTitle = __('voyager-site::seeders.menu_items.content');
        $structureMenuItem = MenuItem::where('menu_id', $menu->id)
            ->whereNull('route')
            ->where('url', '')
            ->whereNull('parent_id')
            ->where(function ($query) use ($contentTitle) {
                $query->where('title', $contentTitle)
                    ->orWhere('icon_class', 'voyager-medal-rank-star');
            })
            ->first();

        if ($structureMenuItem) {
            if ($refresh) {
                $structureMenuItem->fill([
                    'title' => $contentTitle,
                    'target' => '_self',
                    'icon_class' => 'voyager-medal-rank-star',
                    'color' => null,
                    'parent_id' => null,
                    'order' => 99,
                ])->save();
            }
        } else {
            $structureMenuItem = MenuItem::create([
                'menu_id' => $menu->id,
                'title' => $contentTitle,
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-medal-rank-star',
                'color' => null,
                'parent_id' => null,
                'order' => 99,
            ]);
        }


        // PAGES
        $this->seedMenuItem(
            $refresh,
            $menu,
            ['route' => 'voyager.pages.index'],
            [
                'title' => __('voyager-site::seeders.menu_items.pages'),
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-file-text',
                'color' => null,
                'parent_id' => $structureMenuItem->id,
                'order' => 1,
            ]
        );

        // BLOCKS
        $this->seedMenuItem(
            $refresh,
            $menu,
            ['route' => 'voyager.blocks.index'],
            [
                'title' => __('voyager-site::seeders.menu_items.block_and_widgets'),
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-puzzle',
                'color' => null,
                'parent_id' => $structureMenuItem->id,
                'order' => 2,
            ]
        );

        // FORMS
        $this->seedMenuItem(
            $refresh,
            $menu,
            ['route' => 'voyager.forms.index'],
            [
                'title' => __('voyager-site::seeders.menu_items.forms'),
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-window-list',
                'color' => null,
                'parent_id' => $structureMenuItem->id,
                'order' => 3,
            ]
        );

        // SYSTEM PAGES
        $this->seedMenuItem(
            $refresh,
            $menu,
            ['route' => 'voyager.system-pages.index'],
            [
                'title' => __('voyager-site::seeders.menu_items.system_pages'),
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-file-code',
                'color' => null,
                'parent_id' => $structureMenuItem->id,
                'order' => 4,
            ]
        );


        // LOCALIZATIONS
        $this->seedMenuItem(
            $refresh,
            $menu,
            ['route' => 'voyager.localizations.index'],
            [
                'title' => __('voyager-site::seeders.menu_items.localizations'),
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-font',
                'color' => null,
                'parent_id' => $structureMenuItem->id,
                'order' => 5,
            ]
        );


        // SITE SETTINGS
        $this->seedMenuItem(
            $refresh,
            $menu,
            ['route' => 'voyager.site-settings.index'],
            [
                'title' => __('voyager-site::seeders.menu_items.site_settings'),
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-tools',
                'color' => null,
                'parent_id' => $structureMenuItem->id,
                'order' => 6,
            ]
        );

        // REGIONS
        $this->seedMenuItem(
            $refresh,
            $menu,
            ['route' => 'voyager.block-regions.index'],
            [
                'title' => __('voyager-site::seeders.menu_items.regions'),
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-crop',
                'color' => null,
                'parent_id' => $structureMenuItem->id,
                'order' => 7,
            ]
        );


        \Cache::forget('voyager_menu_'.$menu->name);

    }

    protected function seedMenuItem(bool $refresh, Menu $menu, array $criteria, array $payload): MenuItem
    {
        $criteria = array_merge(['menu_id' => $menu->id], $criteria);
        $payload = array_merge(['menu_id' => $menu->id], $payload);

        $item = MenuItem::where($criteria)->first();
        if ($item) {
            if ($refresh) {
                $item->fill($payload)->save();
            }
            return $item;
        }

        return MenuItem::create($payload);
    }
}
