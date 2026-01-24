<?php

namespace MonstreX\VoyagerSite\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\HasMedia;


class SystemPage extends Model
{
    use HasMedia;

    protected $guarded = [];

    protected $perPage = 50;
}
