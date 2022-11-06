<?php

namespace Skinoniks\Laravel\Modules\Exceptions;

use Exception;
use Throwable;

class ModuleNotFoundException extends Exception
{
    public readonly string $moduleId;

    public function __construct(string $moduleId, int $code = 0, ?Throwable $previous = null)
    {
        $this->moduleId = $moduleId;

        parent::__construct("Module [$moduleId] not found.", $code, $previous);
    }
}
