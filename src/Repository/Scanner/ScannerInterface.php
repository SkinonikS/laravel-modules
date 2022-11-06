<?php

namespace SkinonikS\Laravel\Modules\Repository\Scanner;

use SkinonikS\Laravel\Modules\Module\Factory\ModuleFactoryInterface;

interface ScannerInterface
{
    public function scan(array $paths): array;

    public function setModuleFactory(ModuleFactoryInterface $moduleFactory): self;

    public function getModuleFactory(): ModuleFactoryInterface;
}
