<?php

namespace SkinonikS\Laravel\Modules\Facades;

use Illuminate\Support\Facades\Facade;

class Modules extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'modules';
    }
}