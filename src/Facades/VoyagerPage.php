<?php

namespace MonstreX\VoyagerSite\Facades;

use Illuminate\Support\Facades\Facade;

class VoyagerPage extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'vpage';
    }
}