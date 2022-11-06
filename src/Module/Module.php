<?php

namespace SkinonikS\Laravel\Modules\Module;

use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use SkinonikS\Laravel\Modules\Module\Activators\ActivatorRepository;
use SkinonikS\Laravel\Modules\Module\Bootstrap\Resolver\ResolverInterface as BootstrapperResolverInterface;

class Module
{
    public function __construct(
        protected Manifest $manifest,
        protected ?Application $app = null,
    ) {
        if (! $app) {
            $this->app = Container::getInstance();
        }
    }

    public function is(string $id): bool
    {
        return (string) $this->getManifest()->get('id', '') === $id;
    }

    public function isBooted(): bool
    {
        return $this->getBootstrapper()->isBootstrapped($this);
    }

    public function boot(): self
    {
        $this->getBootstrapper()->boot($this);

        return $this;
    }

    public function enable(): self
    {
        if ($this->isDisabled()) {
            $this->getActivator()->setActivationStatus($this, true);
        }

        return $this;
    }

    public function disable(): self
    {
        if ($this->isEnabled()) {
            $this->getActivator()->setActivationStatus($this, false);
        }

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->getActivator()->getActivationStatus($this);
    }

    public function isDisabled(): bool
    {
        return ! $this->isEnabled();
    }

    public function modulePath(string $path = ''): string
    {
        $directory = $this->getManifest()->getDirectory();

        return $directory.($path ? DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : $path);
    }

    public function getManifest(): Manifest
    {
        return $this->manifest;
    }

    public function getActivator(): ActivatorRepository
    {
        return $this->app['modules.activators.driver'];
    }

    public function getBootstrapper(): BootstrapperResolverInterface
    {
        return $this->app['modules.bootstrapper'];
    }
}
