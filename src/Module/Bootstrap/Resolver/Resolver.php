<?php

namespace SkinonikS\Laravel\Modules\Module\Bootstrap\Resolver;

use Illuminate\Contracts\Events\Dispatcher as EventDispatcherContract;
use SkinonikS\Laravel\Modules\Module\Bootstrap\BootstrapperInterface;
use SkinonikS\Laravel\Modules\Module\Module;

class Resolver implements ResolverInterface
{
    protected array $bootstrappedModules = [];

    protected array $bootstrappers = [];

    public function __construct(
        protected EventDispatcherContract $events,
    ) {
        //
    }

    public function registerMany(array $bootstrappers): self
    {
        foreach ($bootstrappers as $bootstrapper) {
            $this->register($bootstrapper);
        }

        return $this;
    }

    public function register(BootstrapperInterface $bootstrapper): self
    {
        $this->bootstrappers[$bootstrapper::class] = $bootstrapper;

        return $this;
    }

    public function boot(Module $module): void
    {
        if ($this->isBootstrapped($module)) {
            return;
        }

        foreach ($this->bootstrappers as $bootstrapper) {
            $this->events->dispatch('module-bootstrapping:'.$bootstrapper::class, [$module]);

            $bootstrapper->bootstrap($module);

            $this->events->dispatch('module-bootstrapped:'.$bootstrapper::class, [$module]);

            $this->bootstrappedModules[] = $module::class;
        }
    }

    public function isBootstrapped(Module $module): bool
    {
        return in_array($module::class, $this->bootstrappedModules, true);
    }
}
