<?php

namespace MonstreX\VoyagerSite\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class SystemPage extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $guarded = [];

    protected $perPage = 50;
}
