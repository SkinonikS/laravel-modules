<?php

namespace SkinonikS\Laravel\Modules\Commands\Generators;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use RuntimeException;
use SkinonikS\Laravel\Modules\Repository\Repository;

class ModelGenerator
{
    protected static $reservedNames = [
        '__halt_compiler',
        'abstract',
        'and',
        'array',
        'as',
        'break',
        'callable',
        'case',
        'catch',
        'class',
        'clone',
        'const',
        'continue',
        'declare',
        'default',
        'die',
        'do',
        'echo',
        'else',
        'elseif',
        'empty',
        'enddeclare',
        'endfor',
        'endforeach',
        'endif',
        'endswitch',
        'endwhile',
        'enum',
        'eval',
        'exit',
        'extends',
        'final',
        'finally',
        'fn',
        'for',
        'foreach',
        'function',
        'global',
        'goto',
        'if',
        'implements',
        'include',
        'include_once',
        'instanceof',
        'insteadof',
        'interface',
        'isset',
        'list',
        'match',
        'namespace',
        'new',
        'or',
        'print',
        'private',
        'protected',
        'public',
        'readonly',
        'require',
        'require_once',
        'return',
        'static',
        'switch',
        'throw',
        'trait',
        'try',
        'unset',
        'use',
        'var',
        'while',
        'xor',
        'yield',
        '__CLASS__',
        '__DIR__',
        '__FILE__',
        '__FUNCTION__',
        '__LINE__',
        '__METHOD__',
        '__NAMESPACE__',
        '__TRAIT__',
    ];

    protected ?string $moduleId = null;

    protected string $stubName = 'module';

    public function __construct(
        protected Application $app,
        protected Filesystem $files,
        protected Repository $repository,
    ) {
        //
    }

    public function useModuleId(string $id): self
    {
        $this->moduleId = $id;

        return $this;
    }

    public function useStubName(string $name): self
    {
        $this->stubName = $name;

        return $this;
    }

    public function generate()
    {
        if (! $this->moduleId) {
            throw new RuntimeException('Module ID is not provided.');
        }

        if (! $this->repository->has($this->moduleId)) {
            throw new RuntimeException("Module [{$this->moduleId}] already exists.");
        }

        if ($this->isReservedName($this->moduleId)) {
            throw new RuntimeException("The name [{$this->moduleId}] is reserved by PHP.");
        }
    }

    protected function isReservedName(string $name): bool
    {
        return in_array(strtolower($name), static::$reservedNames);
    }

    protected function makeManifest(): void
    {
    }

    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = $this->app->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }
}
