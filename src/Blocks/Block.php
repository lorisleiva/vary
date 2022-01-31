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

    public function select(Closure $callback, ?Closure $replace = null, int $limit = -1, bool $includeEol = false): Variant
    {
        return $this->variant->selectPattern($this->getPattern($includeEol), $callback, $replace, $limit);
    }

    public function selectWithEol(Closure $callback, ?Closure $replace = null, int $limit = -1): Variant
    {
        return $this->select($callback, $replace, $limit, true);
    }

    public function prepend(string ...$values): Variant
    {
        $value = join(PHP_EOL, $values) . PHP_EOL;

        return $this->select(
            callback: fn(Variant $variant) => $variant->prepend($value),
            limit: 1,
        );
    }

    public function prependBeforeEach(string ...$values): Variant
    {
        $value = join(PHP_EOL, $values) . PHP_EOL;

        return $this->select(fn(Variant $variant) => $variant->prepend($value));
    }

    public function append(string ...$values): Variant
    {
        $value = PHP_EOL . join(PHP_EOL, $values);

        return $this->select(
            callback: fn(Variant $variant) => $variant->append($value),
            limit: 1,
        );
    }

    public function appendAfterEach(string ...$values): Variant
    {
        $value = PHP_EOL . join(PHP_EOL, $values);

        return $this->select(fn(Variant $variant) => $variant->append($value));
    }

    public function replace(string $search, string $replace): Variant
    {
        return $this->select(fn(Variant $variant) => $variant->replace($search, $replace));
    }

    public function replaceAll(array $replacements): Variant
    {
        return $this->select(fn(Variant $variant) => $variant->replaceAll($replacements));
    }

    public function deleteLine(string $search, int $limit = -1, bool $ignoreWhitespace = true): Variant
    {
        return $this->selectWithEol(fn (Variant $variant) => $variant->deleteLine($search, $limit, $ignoreWhitespace));
    }

    public function empty(): Variant
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
