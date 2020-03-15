<?php

namespace MonstreX\VoyagerSite\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Block.
 *
 * @package namespace App\Models;
 */
class SiteSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $perPage = 50;

//    public function setDetailsAttribute($value)
//    {
//        $this->attributes['details'] = json_encode($value, JSON_PRETTY_PRINT);
//    }

//    public function getDetailsAttribute($value)
//    {
//        return json_decode(!empty($value) ? $value : '{}');
//    }

}
