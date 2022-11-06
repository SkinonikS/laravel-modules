<?php

namespace SkinonikS\Laravel\Modules\Module\Activators;

use Illuminate\Contracts\Events\Dispatcher as Events;
use Illuminate\Support\Manager as BaseManager;

class ActivatorManager extends BaseManager
{
    public function createFileDriver(): ActivatorRepository
    {
        $config = $this->config->get('modules.activators.file');

        $activator = new FileActivator(
            $this->container['files'],
            $config['path'],
        );

        return $this->createRepository($activator);
    }

    protected function createRepository(ActivatorInterface $activator): ActivatorRepository
    {
        return new ActivatorRepository(
            $activator,
            $this->container[Events::class],
        );
    }

    public function getDefaultDriver()
    {
        return $this->config->get('modules.activator');
    }
}
