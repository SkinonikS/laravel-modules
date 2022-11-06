<?php

namespace SkinonikS\Laravel\Modules\Module\Bootstrap;

use SkinonikS\Laravel\Modules\Module\Module;

interface BootstrapperInterface
{
    public function bootstrap(Module $module): void;
}
