<?php

namespace MonstreX\VoyagerSite\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class Block.
 *
 * @package namespace App\Models;
 */
class SiteSetting extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $perPage = 50;

    public function getSettingsGroup($key)
    {
        $settings = $this->where('key', $key)->first();
        if($settings) {
            $values = [];
            foreach (json_decode($settings->details)->fields as $key => $value) {
                if ($value->type !== 'section') {
                    $values[$key] = $value->value;
                }
            }
            return $values;
        }
        return null;
    }

}
