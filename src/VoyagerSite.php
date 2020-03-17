<?php


namespace MonstreX\VoyagerSite;

use Illuminate\Database\Eloquent\Model;
use MonstreX\VoyagerSite\Models\SiteSetting as Settings;

class VoyagerSite
{
    protected $setting_cache = [];


    /*
     * Site Settings
     */
    public function setting($key, $default = null)
    {
        $keys = explode('.', $key);

        $alias = $keys[0];

        $settings = Settings::where('key', $alias)->first();

        $config = json_decode($settings->details);

        if (!isset($this->setting_cache[$alias]) || $this->setting_cache[$alias] === null) {

            foreach ($config->fields as $keyField => $field) {
                if($field->type != 'section') {
                    $this->setting_cache[$alias][$keyField] = $field->value;
                }
            }

        }

        return $this->setting_cache[$keys[0]][$keys[1]] ?: $default;
    }


    public function storeMediaFile(Model $class, $field):string
    {
        $result = $class->addMediaFromRequest($field)->toMediaCollection($field);
        return $result;
    }


}