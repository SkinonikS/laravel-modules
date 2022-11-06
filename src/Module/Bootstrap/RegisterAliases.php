<?php

namespace SkinonikS\Laravel\Modules\Module\Bootstrap;

use Illuminate\Foundation\AliasLoader;
use SkinonikS\Laravel\Modules\Module\Module;

class RegisterAliases implements BootstrapperInterface
{
    public function bootstrap(Module $module): void
    {
        $loader = AliasLoader::getInstance();

        foreach ($module->getManifest()->get('autoload.aliases', []) as $alias => $class) {
            $loader->alias($alias, $class);
        }
    }
}
