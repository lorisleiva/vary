<?php

namespace Lorisleiva\Vary\Concerns;

use Illuminate\Support\Str;

trait ProvidesAccessors
{
    public function getPath(): ?string
    {
        return $this->path;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function match(string $pattern): string
    {
        return Str::match($pattern, $this->value);
    }

    public function matchAll(string $pattern): array
    {
        return Str::matchAll($pattern, $this->value)->toArray();
    }
}
