<?php

namespace SkinonikS\Laravel\Modules\Repository\Scanner;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use SkinonikS\Laravel\Modules\Module\Factory\ModuleFactoryInterface;
use SkinonikS\Laravel\Modules\Module\Manifest;

class Scanner implements ScannerInterface
{
    public function __construct(
        protected ModuleFactoryInterface $moduleFactory,
        protected Filesystem $files,
    ) {
        //
    }

    public function scan(array $paths): array
    {
        // TODO: Add ability to scan multiple paths
        $paths = array_map(function ($path) {
            return Str::endsWith($path, '/*') ? $path : Str::finish($path, '/*');
        }, $paths);

        $modules = [];

        foreach ($paths as $path) {
            $files = $this->files->glob($path.DIRECTORY_SEPARATOR.'manifest.php');

            foreach ($files as $file) {
                $manifest = $this->files->getRequire($file);

                if (! is_array($manifest) || ! array_key_exists('id', $manifest) || empty($id = $manifest['id'])) {
                    continue;
                }

                if (array_key_exists($id, $modules)) {
                    continue;
                }

                $modules[$id] = $this->getModuleFactory()->make(new Manifest($manifest, realpath(dirname($file))));
            }
        }

        return array_values($modules);
    }

    public function setModuleFactory(ModuleFactoryInterface $moduleFactory): self
    {
        $this->moduleFactory = $moduleFactory;

        return $this;
    }

    public function getModuleFactory(): ModuleFactoryInterface
    {
        return $this->moduleFactory;
    }
}
