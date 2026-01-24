<?php

namespace MonstreX\VoyagerSite\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Input\InputOption;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;
use MonstreX\VoyagerSite\Models\SiteSetting;

class UninstallCommand extends Command
{
    protected $name = 'voyager-site:uninstall';

    protected $description = 'Uninstall the Voyager Site package';

    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force the operation without confirmation', null],
        ];
    }

    public function handle()
    {
        $targets = $this->gatherTargets();

        if (!$targets['has_any']) {
            $this->info('No Voyager Site data found to uninstall.');
            return;
        }

        $this->warn('This will remove Voyager Site tables and related BREAD data.');
        $this->line($this->formatTargets($targets));

        if (!$this->option('force')) {
            if (!$this->confirm('Proceed with Voyager Site uninstall?', false)) {
                $this->info('Uninstall canceled.');
                return;
            }
        }

        $this->removeMenuItems($targets);
        $this->removeBreadData($targets);
        $this->removePermissions($targets);
        $this->removeSiteSettings($targets);
        $this->dropTables($targets);
        $this->removeMigrationRecords();

        $this->info('Successfully uninstalled Voyager Site package.');
    }

    protected function gatherTargets()
    {
        $tables = [
            'block_regions',
            'blocks',
            'forms',
            'localizations',
            'site_settings',
            'pages',
            'system_pages',
        ];
        $slugs = [
            'block-regions',
            'blocks',
            'forms',
            'localizations',
            'site-settings',
            'pages',
            'system-pages',
        ];
        $routes = [
            'voyager.pages.index',
            'voyager.blocks.index',
            'voyager.forms.index',
            'voyager.system-pages.index',
            'voyager.localizations.index',
            'voyager.site-settings.index',
            'voyager.block-regions.index',
        ];

        $existingTables = array_values(array_filter($tables, function ($table) {
            return Schema::hasTable($table);
        }));

        $dataTypes = collect();
        $dataTypeIds = [];
        if (Schema::hasTable('data_types')) {
            $dataTypes = DataType::whereIn('slug', $slugs)->get();
            $dataTypeIds = $dataTypes->pluck('id')->all();
        }

        $menu = null;
        if (Schema::hasTable('menus')) {
            $menu = Menu::where('name', 'admin')->first();
        }
        $menuItemIds = [];
        $menuRootId = null;
        if ($menu && Schema::hasTable('menu_items')) {
            $menuItemIds = MenuItem::where('menu_id', $menu->id)
                ->whereIn('route', $routes)
                ->pluck('id')
                ->all();

            $menuRootId = MenuItem::where('menu_id', $menu->id)
                ->whereNull('parent_id')
                ->where('icon_class', 'voyager-medal-rank-star')
                ->where('order', 99)
                ->where(function ($query) {
                    $query->whereNull('route')->orWhere('route', '');
                })
                ->where(function ($query) {
                    $query->where('url', '')->orWhereNull('url');
                })
                ->value('id');
        }

        return [
            'tables' => $existingTables,
            'data_types' => $dataTypes->pluck('slug')->all(),
            'data_type_ids' => $dataTypeIds,
            'menu_item_ids' => $menuItemIds,
            'menu_root_id' => $menuRootId,
            'has_any' => !empty($existingTables) || !empty($dataTypeIds) || !empty($menuItemIds) || $menuRootId !== null,
        ];
    }

    protected function formatTargets(array $targets)
    {
        $lines = [];
        if (!empty($targets['tables'])) {
            $lines[] = 'Tables: ' . implode(', ', $targets['tables']);
        }
        if (!empty($targets['data_types'])) {
            $lines[] = 'BREAD: ' . implode(', ', $targets['data_types']);
        }
        if (!empty($targets['menu_item_ids']) || $targets['menu_root_id'] !== null) {
            $lines[] = 'Menu items: ' . (count($targets['menu_item_ids']) + ($targets['menu_root_id'] !== null ? 1 : 0));
        }

        return implode(PHP_EOL, $lines);
    }

    protected function removeMenuItems(array $targets)
    {
        if (!empty($targets['menu_item_ids'])) {
            MenuItem::whereIn('id', $targets['menu_item_ids'])->delete();
        }

        if ($targets['menu_root_id'] !== null) {
            MenuItem::where('id', $targets['menu_root_id'])->delete();
        }
    }

    protected function removeBreadData(array $targets)
    {
        if (!empty($targets['data_type_ids']) && Schema::hasTable('data_rows') && Schema::hasTable('data_types')) {
            DataRow::whereIn('data_type_id', $targets['data_type_ids'])->delete();
            DataType::whereIn('id', $targets['data_type_ids'])->delete();
        }
    }

    protected function removePermissions(array $targets)
    {
        $tableNames = [
            'block_regions',
            'blocks',
            'forms',
            'localizations',
            'site_settings',
            'pages',
            'system_pages',
        ];

        if (!Schema::hasTable('permissions')) {
            return;
        }

        $permissionIds = Permission::whereIn('table_name', $tableNames)
            ->pluck('id')
            ->all();

        if (!empty($permissionIds)) {
            if (Schema::hasTable('permission_role')) {
                DB::table('permission_role')->whereIn('permission_id', $permissionIds)->delete();
            }
            Permission::whereIn('id', $permissionIds)->delete();
        }
    }

    protected function removeSiteSettings(array $targets)
    {
        if (Schema::hasTable('site_settings')) {
            SiteSetting::whereIn('key', ['general', 'mail', 'seo', 'theme'])->delete();
        }
    }

    protected function dropTables(array $targets)
    {
        $dropOrder = [
            'blocks',
            'block_regions',
            'forms',
            'localizations',
            'site_settings',
            'pages',
            'system_pages',
        ];

        $tables = array_values(array_unique(array_intersect($dropOrder, $targets['tables'])));

        Schema::disableForeignKeyConstraints();
        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
        Schema::enableForeignKeyConstraints();
    }

    protected function removeMigrationRecords()
    {
        if (!Schema::hasTable('migrations')) {
            return;
        }

        $migrations = [
            '2020_03_03_000000_create_blocks_table',
            '2020_03_03_000001_create_forms_table',
            '2020_03_03_000002_create_localizations_table',
            '2020_03_03_000003_create_site_settings_table',
            '2020_03_03_000004_create_site_pages_table',
            '2026_02_01_000000_drop_layout_from_pages_table',
        ];

        DB::table('migrations')->whereIn('migration', $migrations)->delete();
    }
}
