<?php

namespace MonstreX\VoyagerSite\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class SystemPage extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];

    protected $perPage = 50;
}
