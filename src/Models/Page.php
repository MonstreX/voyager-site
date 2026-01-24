<?php

namespace MonstreX\VoyagerSite\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\HasMedia;


class Page extends Model
{
    use HasMedia;

    protected $perPage = 50;
    protected $guarded = [];

}
