<?php

namespace SkinonikS\Laravel\Modules\Module\Activators;

use Illuminate\Events\Dispatcher as EventDispatcher;
use SkinonikS\Laravel\Modules\Module\Module;

class ActivatorRepository
{
    public function __construct(
        protected ActivatorInterface $activator,
        protected EventDispatcher $events,
    ) {
        //
    }

    public function getActivationStatus(Module $module): bool
    {
        return $this->activator->getActivationStatus($module);
    }

    public function setActivationStatus(Module $module, bool $status): void
    {
        $moduleStatus = $this->getActivationStatus($module);

        if ($status && ! $moduleStatus) {
            $this->activator->setActivationStatus($module, $status);

            $this->events->dispatch('module-enabled', [$module]);
        } elseif (! $status && $moduleStatus) {
            $this->activator->setActivationStatus($module, $status);

            $this->events->dispatch('module-enabled', [$module]);
        }
    }
}
