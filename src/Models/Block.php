<?php

namespace MonstreX\VoyagerSite\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;


/**
 * Class Block.
 *
 * @package namespace App\Models;
 */
class Block extends Model implements HasMedia
{
    use HasMediaTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function positionId(){
        return $this->belongsTo(BlockRegion::class, 'region_id', 'id');
    }

    public function position()
    {
        return $this->belongsTo(BlockRegion::class);
    }

}
