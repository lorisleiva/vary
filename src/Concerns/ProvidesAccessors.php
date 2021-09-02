<?php

namespace Lorisleiva\Vary\Concerns;

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
}
