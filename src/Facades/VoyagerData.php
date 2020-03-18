<?php

namespace MonstreX\VoyagerSite\Facades;

use Illuminate\Support\Facades\Facade;

class VoyagerData extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'vdata';
    }
}