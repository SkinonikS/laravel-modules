<?php

namespace SkinonikS\Laravel\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;

class ModulesClearCacheCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected $signature = 'modules:cache-clear';

    /**
     * {@inheritDoc}
     */
    protected $description = '';

    public function __construct(
        protected Application $app,
        protected Filesystem $files,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    public function handle()
    {
        $this->clearManfiestsCache();
        $this->clearModuleServicesCache();

        $this->components->info('Modules cache cleared successfully.');

        return Command::SUCCESS;
    }

    protected function clearModuleServicesCache(): void
    {
        $cachePath = config('modules.cache.paths.module-services');

        $filePattern = 'module-*-services.php';

        $finalPath = $cachePath.DIRECTORY_SEPARATOR.$filePattern;

        foreach ($this->files->glob($finalPath) as $file) {
            $this->files->delete($file);
        }
    }

    protected function clearManfiestsCache()
    {
        $cachePath = config('modules.cache.paths.manifests');

        $filePattern = 'modules-*-manifests.php';

        $finalPath = $cachePath.DIRECTORY_SEPARATOR.$filePattern;

        foreach ($this->files->glob($finalPath) as $file) {
            $this->files->delete($file);
        }
    }
}
