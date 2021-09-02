<?php

namespace Lorisleiva\Vary\Concerns;

use Closure;
use Lorisleiva\Vary\Variant;

trait AltersWhitespace
{
    public function selectBeforeWhitespace(Closure $callback): static
    {
        return $this->selectPatternFirstGroup('/^(.*?)\s*$/s', $callback);
    }

    public function selectAfterWhitespace(Closure $callback): static
    {
        return $this->selectPatternFirstGroup('/^\s*(.*)$/s', $callback);
    }

    public function selectBetweenWhitespace(Closure $callback): static
    {
        return $this->selectAfterWhitespace(
            fn (Variant $variant) => $variant->selectBeforeWhitespace($callback)
        );
    }

    public function prependAfterWhitespace(string $prefix): static
    {
        return $this->selectAfterWhitespace(
            fn (Variant $variant) => $variant->prepend($prefix),
        );
    }

    public function appendBeforeWhitespace(string $suffix): static
    {
        return $this->selectBeforeWhitespace(
            fn (Variant $variant) => $variant->append($suffix),
        );
    }

    public function getLeftWhitespace(): string
    {
        preg_match('/^(\s*)/', $this->value, $matches);

        return $matches[1] ?? '';
    }

    public function getRightWhitespace(): string
    {
        preg_match('/(\s*)$/', $this->value, $matches);

        return $matches[1] ?? '';
    }
}
