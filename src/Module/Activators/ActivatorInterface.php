<?php

namespace SkinonikS\Laravel\Modules\Module\Activators;

use SkinonikS\Laravel\Modules\Module\Module;

interface ActivatorInterface
{
    public function setActivationStatus(Module $module, bool $status): self;

    public function getActivationStatus(Module $module): bool;
}
