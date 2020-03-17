<?php

namespace MonstreX\VoyagerSite\Facades;

use Illuminate\Support\Facades\Facade;

class VoyagerSite extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'vsite';
    }
}