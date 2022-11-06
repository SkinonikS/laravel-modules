<?php

namespace SkinonikS\Laravel\Modules\Module\Factory;

use SkinonikS\Laravel\Modules\Module\Manifest;
use SkinonikS\Laravel\Modules\Module\Module;

interface ModuleFactoryInterface
{
    public function make(Manifest $manifest): Module;
}
