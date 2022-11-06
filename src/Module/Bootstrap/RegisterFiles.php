<?php

namespace SkinonikS\Laravel\Modules\Module\Bootstrap;

use SkinonikS\Laravel\Modules\Module\Module;

class RegisterFiles implements BootstrapperInterface
{
    public function bootstrap(Module $module): void
    {
        foreach ($module->getManifest()->get('autoload.files', []) as $path) {
            require_once $path;
        }
    }
}
