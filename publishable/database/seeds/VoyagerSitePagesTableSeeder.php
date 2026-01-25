<?php

use Illuminate\Database\Seeder;
use MonstreX\VoyagerSite\Models\Page;
use MonstreX\VoyagerSite\Models\SystemPage;

class VoyagerSitePagesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $refresh = config('voyager-site.seed_refresh', false);
        $this->seedPage($refresh, Page::class, 'home', __('voyager-site::seeders.pages.home_title'));
        $this->seedPage($refresh, SystemPage::class, 'error-403', __('voyager-site::seeders.system_pages.403_title'));
        $this->seedPage($refresh, SystemPage::class, 'error-404', __('voyager-site::seeders.system_pages.404_title'));

    }

    protected function seedPage(bool $refresh, string $model, string $slug, string $title): void
    {
        $payload = [
            'title' => $title,
            'status' => 1,
        ];

        if ($refresh) {
            $model::updateOrCreate(['slug' => $slug], $payload);
            return;
        }

        $model::firstOrCreate(['slug' => $slug], $payload);
    }
}
