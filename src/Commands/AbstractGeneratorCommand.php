<?php

namespace SkinonikS\Laravel\Modules\Commands\Generators;

use Illuminate\Console\GeneratorCommand as IlluminateGeneratorCommand;

abstract class GeneratorCommand extends IlluminateGeneratorCommand
{
    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }
}
