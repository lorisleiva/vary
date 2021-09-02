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
            ? $this->selectBefore(PHP_EOL, $callback, included: $includeEol)
            : $this->tap($callback);
    }

    public function lastLine(Closure $callback, bool $includeEol = false): static
    {
        return str_contains($this->value, PHP_EOL)
            ? $this->selectAfter(PHP_EOL, $callback, last: true, included: $includeEol)
            : $this->tap($callback);
    }

    public function matchLine(string $pattern, Closure $callback, int $limit = -1, bool $includeEol = false): static
    {
        $regex = $includeEol ? "/^.*$pattern.*$\n?/m" : "/^.*$pattern.*$/m";

        return $this->selectPattern($regex, $callback, null, $limit);
    }

    public function updateLine(string $lineWithoutWhitespace, Closure $callback, int $limit = -1, bool $includeEol = false): static
    {
        $safeLine = preg_quote($lineWithoutWhitespace, '/');
        $regex = $includeEol ? "/^\s*$safeLine\s*$\n?/m" : "/^\s*$safeLine\s*$/m";

        return $this->selectPattern($regex, $callback, null, $limit);
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

    public function deleteFirstLine(): static
    {
        return $this->firstLine(
            callback: fn (Variant $variant) => $variant->empty(),
            includeEol: true,
        );
    }

    public function deleteLastLine(): static
    {
        return $this->lastLine(
            callback: fn (Variant $variant) => $variant->empty(),
            includeEol: true,
        );
    }

    public function deleteLine(string $search, int $limit = -1): static
    {
        $placeholder = $this->getRandomPlaceholder();
        $overrideCallback = fn (Variant $variant) => $variant->override($placeholder);

        return $this->updateLine($search, $overrideCallback, $limit)
            ->deletePlaceholderLines($placeholder);
    }

    public function deleteLineMatches(string $search, int $limit = -1): static
    {
        $placeholder = $this->getRandomPlaceholder();
        $overrideCallback = fn (Variant $variant) => $variant->override($placeholder);

        return $this->matchLine($search, $overrideCallback, $limit)
            ->deletePlaceholderLines($placeholder);
    }

    protected function deletePlaceholderLines(string $placeholder): static
    {
        return $this->delete($placeholder . PHP_EOL)
            ->delete(PHP_EOL . $placeholder)
            ->delete($placeholder);
    }

    protected function getRandomPlaceholder(): string
    {
        return 'lorisleiva_vary_' . Str::random(32);
    }
}
