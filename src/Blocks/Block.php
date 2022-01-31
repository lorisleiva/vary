<?php

namespace Lorisleiva\Vary\Blocks;

use Closure;
use Illuminate\Support\Str;
use Lorisleiva\Vary\Variant;

class Block
{
    protected Variant $variant;
    protected string $pattern;
    protected string $allowedPattern;

    public function __construct(Variant $variant, string $pattern, string $allowedPattern = '\n')
    {
        $this->variant = $variant;
        $this->pattern = $pattern;
        $this->allowedPattern = $allowedPattern;
    }

    public function getPattern(bool $includeEol = false): string
    {
        return sprintf(
            '/%3$s(?:%1$s(?:%2$s)*)*(?:%1$s%3$s)/m',
            $this->pattern,
            $this->allowedPattern,
            $includeEol ? '\n?' : ''
        );
    }

    public function select(Closure $callback, bool $includeEol = false): Variant
    {
        return $this->variant->selectPattern($this->getPattern($includeEol), $callback);
    }

    public function selectWithEol(Closure $callback): Variant
    {
        return $this->select($callback, true);
    }

    public function prepend(): Variant
    {
        return $this->variant; // TODO
    }

    public function prependBeforeEach(): Variant
    {
        return $this->variant; // TODO
    }

    public function append(): Variant
    {
        return $this->variant; // TODO
    }

    public function appendAfterEach(): Variant
    {
        return $this->variant; // TODO
    }

    public function replace(): Variant
    {
        return $this->variant; // TODO
    }

    public function replaceAll(): Variant
    {
        return $this->variant; // TODO
    }

    public function delete(): Variant
    {
        return $this->selectWithEol(function (Variant $variant) {
            $inside = Str::startsWith($variant, PHP_EOL)
                && Str::endsWith($variant, PHP_EOL);

            return $inside ? $variant->override(PHP_EOL) : $variant->empty();
        });
    }

    public function first(bool $includeEol = false): string
    {
        return $this->variant->match($this->getPattern($includeEol));
    }

    public function firstWithEol(): string
    {
        return $this->first(true);
    }

    public function all(bool $includeEol = false): array
    {
        return $this->variant->matchAll($this->getPattern($includeEol));
    }

    public function allWithEol(): array
    {
        return $this->all(true);
    }
}
