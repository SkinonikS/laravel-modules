<?php

namespace SkinonikS\Laravel\Modules\Module\Bootstrap\Resolver;

use SkinonikS\Laravel\Modules\Module\Bootstrap\BootstrapperInterface;
use SkinonikS\Laravel\Modules\Module\Module;

interface ResolverInterface
{
    public function registerMany(array $bootstrappers): self;

    public function register(BootstrapperInterface $bootstrapper): self;

    public function boot(Module $module): void;

    public function isBootstrapped(Module $module): bool;
}
