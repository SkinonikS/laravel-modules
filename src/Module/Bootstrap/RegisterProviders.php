<?php

namespace SkinonikS\Laravel\Modules\Module\Bootstrap;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\ProviderRepository;
use SkinonikS\Laravel\Modules\Module\Module;
use SkinonikS\Laravel\Modules\Repository\Repository;

class RegisterProviders implements BootstrapperInterface
{
    public function __construct(
        protected Application $app,
        protected Filesystem $files,
        protected string $cachePath,
    ) {
        //
    }

    public function bootstrap(Module $module): void
    {
        $cachePath = $this->cachePathFor($module);

        $providers = $module->getManifest()->get('autoload.providers', []);

        (new ProviderRepository($this->app, $this->files, $cachePath))
            ->load($providers);
    }

    public function cachePathFor(Module $module): string
    {
        $version = Repository::getVersion();
        $signature = $module->getManifest()->getSignature();
        $hash = md5($version.$signature);

        $filename = "module-{$hash}-services.php";

        return $this->cachePath.DIRECTORY_SEPARATOR.$filename;
    }

    public function getCachePath(): string
    {
        return $this->cachePath;
    }
}
