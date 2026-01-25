<?php

use Illuminate\Database\Seeder;
use MonstreX\VoyagerSite\Models\BlockRegion as Region;

class VoyagerSiteBlockRegionsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $refresh = config('voyager-site.seed_refresh', false);
        $this->seedRegion($refresh, 'content-before', __('voyager-site::seeders.regions.content_before'), 1, '#2f8516');
        $this->seedRegion($refresh, 'content', __('voyager-site::seeders.regions.content'), 2, '#2f8516');
        $this->seedRegion($refresh, 'content-after', __('voyager-site::seeders.regions.content_after'), 3, '#4bc2a2');
        $this->seedRegion($refresh, 'no-position', __('voyager-site::seeders.regions.no_position'), 4, '#c7c7c7');
        $this->seedRegion($refresh, 'sidebar', __('voyager-site::seeders.regions.sidebar'), 5, '#e20a0f');
    }

    protected function seedRegion(bool $refresh, string $key, string $title, int $order, string $color): void
    {
        $payload = [
            'title' => $title,
            'order' => $order,
            'color' => $color,
        ];

        if ($refresh) {
            Region::updateOrCreate(['key' => $key], $payload);
            return;
        }

        Region::firstOrCreate(['key' => $key], $payload);
    }
}
