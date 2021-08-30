<?php

namespace Lorisleiva\Vary\Concerns;

use Closure;
use Illuminate\Support\Arr;
use Lorisleiva\Vary\Variant;

trait AltersLines
{
    public function firstLine(Closure $callback): static
    {
        return $this->beforeIncluded(PHP_EOL, $callback);
    }

    public function lastLine(Closure $callback): static
    {
        return $this->afterLastIncluded(PHP_EOL, $callback);
    }

    public function matchLine(string $pattern, Closure $callback, int $limit = -1): static
    {
        return $this->match("/^.*$pattern.*$/m", $callback, null, $limit);
    }

    public function updateLine(string $lineWithoutWhitespace, Closure $callback, int $limit = -1): static
    {
        $safeLine = preg_quote($lineWithoutWhitespace, '/');

        return $this->match("/^\s*$safeLine\s*$/m", $callback, null, $limit);
    }

    public function getFirstLine(): string
    {
        return Arr::first(explode(PHP_EOL, $this->value));
    }

    public function getLastLine(): string
    {
        return Arr::last(explode(PHP_EOL, $this->value));
    }

    public function appendLine(string $line, bool $keepIndent = false): static
    {
        $lineJump = $this->value ? PHP_EOL : '';
        $indent = $keepIndent ? $this->getIndentFromLine($this->getLastLine()) : '';

        return $this->new($this->value . "$lineJump$indent$line");
    }

    public function prependLine(string $line, bool $keepIndent = false): static
    {
        $lineJump = $this->value ? PHP_EOL : '';
        $indent = $keepIndent ? $this->getIndentFromLine($this->getFirstLine()) : '';

        return $this->new("$indent$line$lineJump" . $this->value);
    }

    public function addLineAfter(string $search, string $line, bool $keepIndent = false): static
    {
        return $this->updateLine(
            $search,
            fn (Variant $variant) => $variant->appendLine($line, $keepIndent)
        );
    }

    public function addLineAfterMatches(string $search, string $line, bool $keepIndent = false): static
    {
        return $this->matchLine(
            $search,
            fn (Variant $variant) => $variant->appendLine($line, $keepIndent)
        );
    }

    public function addLineBefore(string $search, string $line, bool $keepIndent = false): static
    {
        return $this->updateLine(
            $search,
            fn (Variant $variant) => $variant->prependLine($line, $keepIndent),
        );
    }

    public function addLineBeforeMatches(string $search, string $line, bool $keepIndent = false): static
    {
        return $this->matchLine(
            $search,
            fn (Variant $variant) => $variant->prependLine($line, $keepIndent),
        );
    }

    public function removeFirstLine(): static
    {
        $lines = explode(PHP_EOL, $this->value);
        array_shift($lines);

        return $this->new(implode(PHP_EOL, $lines));
    }

    public function removeLastLine(): static
    {
        $lines = explode(PHP_EOL, $this->value);
        array_pop($lines);

        return $this->new(implode(PHP_EOL, $lines));
    }

    protected function getIndentFromLine(string $line): string
    {
        preg_match('/^(\s*)/', $line, $matches);

        return $matches[1] ?? '';
    }
}
