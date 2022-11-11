<?php

use SkinonikS\Laravel\Modules\Module\Module;
use SkinonikS\Laravel\Modules\Repository\Repository;

if (! function_exists('modules')) {
    function modules(): Repository
    {
        return app(Repository::class);
    }
}

if (! function_exists('module')) {
    function module(string $moduleId): ?Module
    {
        return modules()->find($moduleId);
    }
}

if (! function_exists('moduleOrFail')) {
    function moduleOrFail(string $moduleId): Module
    {
        return modules()->findOrFail($moduleId);
    }
}

if (! function_exists('module_path')) {
    function module_path(string $moduleId, string $path = ''): string
    {
        return moduleOrFail($moduleId)
            ->modulePath($path);
    }
}
