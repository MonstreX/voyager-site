<?php


namespace MonstreX\VoyagerSite\Services;

use MonstreX\VoyagerSite\Contracts\VoyagerSite as VoyagerSiteContract;
use Illuminate\Database\Eloquent\Model;
use MonstreX\VoyagerSite\Models\SiteSetting as Settings;

class VoyagerSite implements VoyagerSiteContract
{

    protected $setting_cache = [];

    /**
     * @param $key
     * @param null $default
     * @return Mixed
     */
    public function setting($key, $default = null)
    {
        if (empty($this->setting_cache)) {
            // Create settings cache
            $settings = Settings::all();
            foreach ($settings as $group) {
                $data = json_decode($group->details);
                foreach ($data->fields as $field_name => $field) {
                    if($field->type != 'section' && $field->type != 'route') {
                        if($field->type === 'media') {
                            $media = $group->getMedia($field_name);
                            if (count($media) > 0) {
                                $this->setting_cache[$group->key][$field_name] = $group->getMedia($field_name)[0]->getFullUrl();
                            } else {
                                $this->setting_cache[$group->key][$field_name] = '';
                            }
                        } else {
                            $this->setting_cache[$group->key][$field_name] = $field->value;
                        }
                    }
                }
            }
        }

        $keys = explode('.', $key);

        return $this->setting_cache[$keys[0]][$keys[1]] ?: $default;
    }


    /**
     * Returns SEO Site Settings
     * @return array
     */
    public function getSettings(): array
    {
        return [
            'template'        => config('voyager-site.template'),
            'template_master' => config('voyager-site.template_master'),
            'template_layout' => config('voyager-site.template_layout'),
            'template_page'   => config('voyager-site.template_page'),
            'site_title' => $this->setting('general.site_title'),
            'site_description' => $this->setting('general.site_description'),
            'seo_title_template' => $this->setting('seo.seo_title_template'),
            'seo_title' => $this->setting('seo.seo_title'),
            'meta_description' => $this->setting('seo.meta_description'),
            'meta_keywords' => $this->setting('seo.meta_keywords'),
        ];
    }


    /**
     * @param Model $class
     * @param $field
     * @return string
     */
    public function storeMediaFile(Model $class, $field):string
    {
        $result = $class->addMediaFromRequest($field)->toMediaCollection($field);
        return $result;
    }

    /**
     * @return string
     */
    public function currentPath(): string
    {
        return request()->path();
    }

}