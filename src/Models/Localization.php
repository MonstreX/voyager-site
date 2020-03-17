<?php

namespace MonstreX\VoyagerSite\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Block.
 *
 * @package namespace App\Models;
 */
class Localization extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $perPage = 100;

    public function loadLocalizations()
    {
        $locale = trans()->locale();
        $locLines = [];
        if ($locStrings = $this->all()) {
            //dd($locStrings);
            foreach ($locStrings as $locString) {
                $locLines[$locString->key] = $locString->{$locale};
            }
            trans()->addLines($locLines, $locale);
        }
    }

}
