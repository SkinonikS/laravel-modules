<?php

namespace SkinonikS\Laravel\Modules\Module\Factory;

use SkinonikS\Laravel\Modules\Module\Manifest;
use SkinonikS\Laravel\Modules\Module\Module;

class ModuleFactory implements ModuleFactoryInterface
{
    public function make(Manifest $manifest): Module
    {
        return new Module($manifest);
    }
}
