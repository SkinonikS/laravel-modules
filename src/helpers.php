<?php

use SkinonikS\Laravel\Modules\Repository\Repository;

if (! function_exists('module_path')) {
    function module_path(string $moduleId, string $path = ''): string
    {
        return app(Repository::class)
            ->findOrFail($moduleId)
            ->modulePath($path);
    }
}
