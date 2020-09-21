<?php

namespace MonstreX\VoyagerSite\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Page extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $perPage = 50;
    protected $guarded = [];

}
