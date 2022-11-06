<?php

namespace SkinonikS\Laravel\Modules\Module\Activators;

use Illuminate\Filesystem\Filesystem;
use SkinonikS\Laravel\Modules\Module\Module;

class FileActivator implements ActivatorInterface
{
    protected array $statuses = [];

    public function __construct(
        protected Filesystem $files,
        protected string $path,
    ) {
        $this->loadStatuses();
    }

    public function setActivationStatus(Module $module, bool $status): self
    {
        $signature = $module->getManifest()->getSignature();

        $this->statuses[$signature] = $status;

        $this->files->replace($this->path, '<?php return '.var_export($this->statuses, true).';');

        return $this;
    }

    public function getActivationStatus(Module $module): bool
    {
        $signature = $module->getManifest()->getSignature();

        return array_key_exists($signature, $this->statuses) && (bool) $this->statuses[$signature];
    }

    protected function loadStatuses(): void
    {
        $this->statuses = [];

        if (! $this->files->isReadable($this->path)) {
            return;
        }

        $this->statuses = $this->files->getRequire($this->path);
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
