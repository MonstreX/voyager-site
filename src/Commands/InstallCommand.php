<?php

namespace MonstreX\VoyagerSite\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;
use TCG\Voyager\Traits\Seedable;
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
        $this->info('Publishing the Voyager Site assets, database, and config files');

        // Publish only relevant resources on install
        $tags = ['seeds'];

        $this->call('vendor:publish', ['--provider' => VoyagerSiteServiceProvider::class, '--tag' => $tags, '--force' => true]);

        $this->info('Migrating the database tables into your application');
        $this->call('migrate', ['--force' => $this->option('force')]);

        $this->info('Seeding data into the database');
        $this->seed('VoyagerSiteDatabaseSeeder');

        $this->info('Successfully installed Voyager Site Package! Enjoy');
    }
}
