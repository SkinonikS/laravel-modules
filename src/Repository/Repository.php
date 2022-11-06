<?php

namespace SkinonikS\Laravel\Modules\Repository;

use Illuminate\Filesystem\Filesystem;
use Skinoniks\Laravel\Modules\Exceptions\ModuleNotFoundException;
use SkinonikS\Laravel\Modules\Module\Module;
use SkinonikS\Laravel\Modules\Repository\Scanner\ScannerInterface;

class Repository
{
    protected array $cachedModules = [];

    public const VERSION = '1.0.0';

    public function __construct(
        protected ScannerInterface $scanner,
        //protected Filesystem $files,
        protected string $path,
    ) {
        //
    }

    public function flush(): self
    {
        $this->cachedModules = [];

        return $this;
    }

    public function all(): array
    {
        if ($this->cachedModules) {
            return $this->cachedModules;
        }

        return $this->cachedModules = $this->scanner->scan([
            $this->getPath(),
        ]);
    }

    public function has(string $id): bool
    {
        return $this->find($id) !== null;
    }

    public function find(string $id): ?Module
    {
        /**
         * @var \SkinonikS\Laravel\Modules\Module|null
         */
        $module = collect($this->all())->first(fn (Module $module) => $module->is($id));

        return $module;
    }

    public function findOrFail(string $id): Module
    {
        if ($module = $this->find($id)) {
            return $module;
        }

        throw new ModuleNotFoundException($id);
    }

    // public function delete(string|Module $module): self
    // {
    //     $modulePath = $module instanceof Module
    //         ? $module->modulePath()
    //         : $this->findOrFail($module)->modulePath();

    //     if ($this->files->isDirectory($modulePath)) {
    //         $this->files->delete($modulePath);
    //     }

    //     return $this;
    // }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setScanner(ScannerInterface $scanner): self
    {
        $this->scanner = $scanner;

        return $this;
    }

    public function getScanner(): ScannerInterface
    {
        return $this->scanner;
    }

    public static function getVersion(): string
    {
        return static::VERSION;
    }
}
