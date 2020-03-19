<?php

namespace MonstreX\VoyagerSite\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Page extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $perPage = 50;
    protected $guarded = [];

}
