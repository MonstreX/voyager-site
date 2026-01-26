<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
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
        $structureMenuItem = $this->seedMenuItem(
            $refresh,
            $menu,
            'voyager_site.content',
            ['route' => null, 'icon_class' => 'voyager-world', 'url' => ''],
            [
                'title' => $contentTitle,
                'key' => 'voyager_site.content',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-world',
                'color' => null,
                'parent_id' => null,
                'order' => 99,
            ]
        );


        // PAGES
        $this->seedMenuItem(
            $refresh,
            $menu,
            'voyager_site.pages',
            ['route' => 'voyager.pages.index', 'icon_class' => 'voyager-file-text'],
            [
                'title' => __('voyager-site::seeders.menu_items.pages'),
                'key' => 'voyager_site.pages',
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
            'voyager_site.blocks',
            ['route' => 'voyager.blocks.index', 'icon_class' => 'voyager-puzzle'],
            [
                'title' => __('voyager-site::seeders.menu_items.block_and_widgets'),
                'key' => 'voyager_site.blocks',
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
            'voyager_site.forms',
            ['route' => 'voyager.forms.index', 'icon_class' => 'voyager-window-list'],
            [
                'title' => __('voyager-site::seeders.menu_items.forms'),
                'key' => 'voyager_site.forms',
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
            'voyager_site.system_pages',
            ['route' => 'voyager.system-pages.index', 'icon_class' => 'voyager-file-code'],
            [
                'title' => __('voyager-site::seeders.menu_items.system_pages'),
                'key' => 'voyager_site.system_pages',
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
            'voyager_site.localizations',
            ['route' => 'voyager.localizations.index', 'icon_class' => 'voyager-font'],
            [
                'title' => __('voyager-site::seeders.menu_items.localizations'),
                'key' => 'voyager_site.localizations',
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
            'voyager_site.site_settings',
            ['route' => 'voyager.site-settings.index', 'icon_class' => 'voyager-tools'],
            [
                'title' => __('voyager-site::seeders.menu_items.site_settings'),
                'key' => 'voyager_site.site_settings',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-tools',
                'color' => null,
                'parent_id' => $structureMenuItem->id,
                'order' => 6,
            ]
        );

        // SCRIPTS
        $this->seedMenuItem(
            $refresh,
            $menu,
            'voyager_site.scripts',
            ['route' => 'voyager.site-scripts.index', 'icon_class' => 'voyager-code'],
            [
                'title' => __('voyager-site::seeders.menu_items.scripts'),
                'key' => 'voyager_site.scripts',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-code',
                'color' => null,
                'parent_id' => $structureMenuItem->id,
                'order' => 7,
            ]
        );

        // REDIRECTS
        $this->seedMenuItem(
            $refresh,
            $menu,
            'voyager_site.redirects',
            ['route' => 'voyager.site-redirects.index', 'icon_class' => 'voyager-forward'],
            [
                'title' => __('voyager-site::seeders.menu_items.redirects'),
                'key' => 'voyager_site.redirects',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-forward',
                'color' => null,
                'parent_id' => $structureMenuItem->id,
                'order' => 9,
            ]
        );

        // REGIONS
        $this->seedMenuItem(
            $refresh,
            $menu,
            'voyager_site.block_regions',
            ['route' => 'voyager.block-regions.index', 'icon_class' => 'voyager-crop'],
            [
                'title' => __('voyager-site::seeders.menu_items.regions'),
                'key' => 'voyager_site.block_regions',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-crop',
                'color' => null,
                'parent_id' => $structureMenuItem->id,
                'order' => 10,
            ]
        );


        \Cache::forget('voyager_menu_'.$menu->name);

    }

    protected function seedMenuItem(bool $refresh, Menu $menu, string $key, array $criteria, array $payload): MenuItem
    {
        $hasKeyColumn = Schema::hasColumn('menu_items', 'key');
        $criteria = array_merge(['menu_id' => $menu->id], $criteria);
        $payload = array_merge(['menu_id' => $menu->id], $payload);
        if (!array_key_exists('route', $payload) && array_key_exists('route', $criteria)) {
            $payload['route'] = $criteria['route'];
        }

        if (!$hasKeyColumn) {
            unset($payload['key']);
        }

        $itemByKey = null;
        if ($hasKeyColumn) {
            $itemByKey = MenuItem::whereNotNull('key')
                ->where('menu_id', $menu->id)
                ->where('key', $key)
                ->first();
        }

        $itemByCriteria = MenuItem::where($criteria)->first();
        if ($hasKeyColumn && $itemByCriteria) {
            $itemByCriteria = MenuItem::where($criteria)->whereNull('key')->first() ?: $itemByCriteria;
        }

        if ($itemByCriteria && $itemByKey && $itemByCriteria->id !== $itemByKey->id) {
            if (!$refresh) {
                return $itemByKey;
            }
            $itemByKey->delete();
            if ($hasKeyColumn && empty($itemByCriteria->key)) {
                $itemByCriteria->key = $key;
            }
            $itemByCriteria->fill($this->refreshPayload($payload));
            $this->syncParentIfMissing($itemByCriteria, $payload);
            $itemByCriteria->save();
            return $itemByCriteria;
        }

        if ($itemByKey) {
            if ($refresh) {
                $itemByKey->fill($this->refreshPayload($payload));
                $this->syncParentIfMissing($itemByKey, $payload);
                $itemByKey->save();
            }
            return $itemByKey;
        }

        if ($itemByCriteria) {
            if ($hasKeyColumn && empty($itemByCriteria->key)) {
                $itemByCriteria->key = $key;
            }
            if ($refresh) {
                $itemByCriteria->fill($this->refreshPayload($payload));
                $this->syncParentIfMissing($itemByCriteria, $payload);
            }
            $itemByCriteria->save();
            return $itemByCriteria;
        }

        return MenuItem::create($payload);
    }

    protected function refreshPayload(array $payload): array
    {
        unset($payload['parent_id'], $payload['order']);

        return $payload;
    }

    protected function syncParentIfMissing(MenuItem $item, array $payload): void
    {
        $targetParent = $payload['parent_id'] ?? null;
        if (is_null($item->parent_id)) {
            return;
        }

        $parentExists = MenuItem::where('menu_id', $item->menu_id)
            ->where('id', $item->parent_id)
            ->exists();
        if ($parentExists) {
            return;
        }

        $item->parent_id = $targetParent;
    }
}
