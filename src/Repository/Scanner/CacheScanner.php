<?php

namespace SkinonikS\Laravel\Modules\Repository\Scanner;

use Illuminate\Filesystem\Filesystem;
use SkinonikS\Laravel\Modules\Module\Factory\ModuleFactoryInterface;
use SkinonikS\Laravel\Modules\Module\Manifest;
use SkinonikS\Laravel\Modules\Repository\Repository;

class CacheScanner implements ScannerInterface
{
    public function __construct(
        protected ScannerInterface $scanner,
        protected ModuleFactoryInterface $moduleFactory,
        protected Filesystem $files,
        protected string $path,
    ) {
        $this->setupPath();
    }

    public function scan(array $paths): array
    {
        if ($this->isCached()) {
            return $this->loadFromCache();
        }

        return $this->scanner->scan($paths);
    }

    public function isCached(): bool
    {
        return $this->files->isFile($this->path) && $this->files->isReadable($this->path);
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

    public function getPath(): string
    {
        return $this->path;
    }

    public function getBase(): ScannerInterface
    {
        return $this->scanner;
    }

    protected function setupPath(): void
    {
        $version = Repository::getVersion();

        $fileName = 'modules-'.md5($version).'-manifests.php';

        $this->path = $this->path.DIRECTORY_SEPARATOR.$fileName;
    }

    protected function loadFromCache(): array
    {
        $manfiests = $this->files->getRequire($this->path);

        return array_map(function (array $data) {
            $manifest = new Manifest($data['manifest'], realpath(base_path($data['dir'])));

            return $this->getModuleFactory()->make($manifest);
        }, $manfiests);
    }
}
