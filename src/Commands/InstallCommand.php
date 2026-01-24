<?php

namespace MonstreX\VoyagerSite\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;
use TCG\Voyager\Traits\Seedable;
use TCG\Voyager\Models\DataType;
use MonstreX\VoyagerSite\VoyagerSiteServiceProvider;

class InstallCommand extends Command
{
    use Seedable;

    protected $seedersPath = __DIR__.'/../../publishable/database/seeds/';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'voyager-site:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Voyager Site package';


    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production', null],
        ];
    }

    public function fire(Filesystem $filesystem)
    {
        return $this->handle($filesystem);
    }

    /**
     * Execute the console command.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     *
     * @return void
     */
    public function handle(Filesystem $filesystem)
    {
        $status = $this->detectInstallStatus();
        if ($status['installed']) {
            $this->info('Voyager Site is already installed.');
            $this->line('If you need to remove it, run: php artisan voyager-site:uninstall');
            return;
        }

        if ($status['has_conflicts']) {
            $this->warn('Voyager Site conflicts detected. Installation aborted.');
            $this->line($this->formatConflicts($status['conflicts']));
            $this->line('Remove existing data with: php artisan voyager-site:uninstall');
            return;
        }

        $this->info('Publishing the Voyager Site assets, database, and config files');

        // Publish only relevant resources on install
        $tags = ['config', 'seeds'];

        $this->call('vendor:publish', ['--provider' => VoyagerSiteServiceProvider::class, '--tag' => $tags, '--force' => true]);

        $this->info('Migrating the database tables into your application');
        $this->call('migrate', ['--force' => $this->option('force')]);

        $missingTables = $this->getMissingTables();
        if (!empty($missingTables)) {
            $this->error('Missing tables after migration: ' . implode(', ', $missingTables));
            $this->error('Installation aborted before seeding.');
            return;
        }

        $this->info('Seeding data into the database');
        $this->seed('VoyagerSiteDatabaseSeeder');

        $this->info('Successfully installed Voyager Site Package! Enjoy');
    }

    protected function detectInstallStatus()
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

        $existingTables = array_values(array_filter($tables, function ($table) {
            return Schema::hasTable($table);
        }));

        $existingDataTypes = [];
        if (Schema::hasTable('data_types')) {
            $existingDataTypes = DataType::whereIn('slug', $slugs)
                ->pluck('slug')
                ->all();
        }

        $installed = count($existingTables) === count($tables)
            && count($existingDataTypes) === count($slugs);

        $hasConflicts = !empty($existingTables) || !empty($existingDataTypes);

        return [
            'installed' => $installed,
            'has_conflicts' => $hasConflicts && !$installed,
            'conflicts' => [
                'tables' => $existingTables,
                'data_types' => $existingDataTypes,
            ],
        ];
    }

    protected function formatConflicts(array $conflicts)
    {
        $lines = [];
        if (!empty($conflicts['tables'])) {
            $lines[] = 'Tables: ' . implode(', ', $conflicts['tables']);
        }
        if (!empty($conflicts['data_types'])) {
            $lines[] = 'BREAD: ' . implode(', ', $conflicts['data_types']);
        }

        return implode(PHP_EOL, $lines);
    }

    protected function getMissingTables()
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

        return array_values(array_filter($tables, function ($table) {
            return !Schema::hasTable($table);
        }));
    }
}
