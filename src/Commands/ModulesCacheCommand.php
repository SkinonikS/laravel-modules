<?php

namespace SkinonikS\Laravel\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use SkinonikS\Laravel\Modules\Module\Module;
use SkinonikS\Laravel\Modules\Repository\Repository;

class ModulesCacheCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected $signature = 'modules:cache';

    /**
     * {@inheritDoc}
     */
    protected $description = '';

    public function __construct(
        protected Repository $repository,
        protected Filesystem $files,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    public function handle()
    {
        $this->callSilently('modules:cache-clear');

        $items = collect($this->repository->all())->map(static function (Module $module) {
            $manifest = $module->getManifest();

            return array_merge($manifest->toArray(), [
                'dir' => substr($manifest->getDirectory(), strlen(base_path())),
            ]);
        })->toArray();

        $cachePath = config('modules.cache.paths.manifests');

        $version = Repository::getVersion();

        $fileName = 'modules-'.md5($version).'-manifests.php';

        $finalPath = $cachePath.DIRECTORY_SEPARATOR.$fileName;

        $this->files->put($finalPath, '<?php return '.var_export($items, true).';');

        $this->components->info('Modules cache succesfully created.');

        return Command::SUCCESS;
    }
}
