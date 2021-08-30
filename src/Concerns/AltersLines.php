<?php

namespace Lorisleiva\Vary\Concerns;

use Closure;
use Illuminate\Support\Str;
use Lorisleiva\Vary\Variant;

trait AltersLines
{
    public function getIndentation(): string
    {
        preg_match('/^(\s*)/', $this->value, $matches);

        return $matches[1] ?? '';
    }

    public function firstLine(Closure $callback, bool $includeEol = false): static
    {
        return str_contains($this->value, PHP_EOL)
            ? $this->before(PHP_EOL, $callback, included: $includeEol)
            : $this->tap($callback);
    }

    public function lastLine(Closure $callback, bool $includeEol = false): static
    {
        return str_contains($this->value, PHP_EOL)
            ? $this->after(PHP_EOL, $callback, last: true, included: $includeEol)
            : $this->tap($callback);
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

    public function appendLine(string $line, bool $keepIndent = false): static
    {
        $lineJump = $this->value ? PHP_EOL : '';

        return $this->lastLine(function (Variant $variant) use ($lineJump, $line, $keepIndent) {
            $indent = $keepIndent ? $variant->getIndentation() : '';

            return $variant->append("$lineJump$indent$line");
        });
    }

    public function prependLine(string $line, bool $keepIndent = false): static
    {
        $lineJump = $this->value ? PHP_EOL : '';

        return $this->firstLine(function (Variant $variant) use ($lineJump, $line, $keepIndent) {
            $indent = $keepIndent ? $variant->getIndentation() : '';

            return $variant->prepend("$indent$line$lineJump");
        });
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
        return $this->firstLine(
            callback: fn (Variant $variant) => $variant->empty(),
            includeEol: true,
        );
    }

    public function removeLastLine(): static
    {
        return $this->lastLine(
            callback: fn (Variant $variant) => $variant->empty(),
            includeEol: true,
        );
    }

    public function removeLine(string $search, int $limit = -1): static
    {
        $placeholder = $this->getRandomPlaceholder();
        $overrideCallback = fn (Variant $variant) => $variant->override($placeholder);

        return $this->updateLine($search, $overrideCallback, $limit)
            ->removePlaceholderLines($placeholder);
    }

    public function removeLineMatches(string $search, int $limit = -1): static
    {
        $placeholder = $this->getRandomPlaceholder();
        $overrideCallback = fn (Variant $variant) => $variant->override($placeholder);

        return $this->matchLine($search, $overrideCallback, $limit)
            ->removePlaceholderLines($placeholder);
    }

    protected function removePlaceholderLines(string $placeholder): static
    {
        return $this->replace($placeholder . PHP_EOL, '')
            ->replace(PHP_EOL . $placeholder, '')
            ->replace($placeholder, '');
    }

    protected function getRandomPlaceholder(): string
    {
        return 'lorisleiva_vary_' . Str::random(32);
    }
}
