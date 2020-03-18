<?php

namespace MonstreX\VoyagerSite\Facades;

use Illuminate\Support\Facades\Facade;

class VoyagerBlock extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'vblock';
    }
}