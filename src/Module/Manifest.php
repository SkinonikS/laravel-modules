<?php

namespace SkinonikS\Laravel\Modules\Module;

use Illuminate\Contracts\Support\Arrayable;

class Manifest implements Arrayable
{
    public function __construct(
        protected array $manifest,
        public readonly string $directory,
    ) {
        //
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function getSignature(): string
    {
        return $this->get('id');
    }

    public function get(string $key, $default = null): mixed
    {
        return data_get($this->manifest, $key, $default);
    }

    public function toArray()
    {
        return [
            'manifest' => $this->manifest,
            'dir' => $this->getDirectory(),
        ];
    }
}
