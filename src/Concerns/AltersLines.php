<?php

namespace Lorisleiva\Vary\Concerns;

use Closure;
use Illuminate\Support\Str;
use Lorisleiva\Vary\Variant;

trait AltersLines
{
    public function selectLine(string $search, Closure $callback, int $limit = -1, bool $includeEol = false, bool $ignoreWhitespace = true): static
    {
        $safeSearch = preg_quote($search, '/');
        $regex = $ignoreWhitespace
            ? ($includeEol ? "/^\s*$safeSearch\s*$\n?/m" : "/^\s*$safeSearch\s*$/m")
            : ($includeEol ? "/^$safeSearch$\n?/m" : "/^$safeSearch$/m");

        return $this->selectPattern($regex, $callback, null, $limit);
    }

    public function selectLineWithEol(string $search, Closure $callback, int $limit = -1): static
    {
        return $this->selectLine($search, $callback, $limit, includeEol: true);
    }

    public function selectExactLine(string $search, Closure $callback, int $limit = -1): static
    {
        return $this->selectLine($search, $callback, $limit, ignoreWhitespace: false);
    }

    public function selectExactLineWithEol(string $search, Closure $callback, int $limit = -1): static
    {
        return $this->selectLine($search, $callback, $limit, includeEol: true, ignoreWhitespace: false);
    }

    public function selectLinePattern(string $pattern, Closure $callback, int $limit = -1, bool $includeEol = false): static
    {
        $regex = $includeEol ? "/^.*$pattern.*$\n?/m" : "/^.*$pattern.*$/m";

        return $this->selectPattern($regex, $callback, null, $limit);
    }

    public function selectFirstLine(Closure $callback, bool $includeEol = false): static
    {
        return str_contains($this->value, PHP_EOL)
            ? $this->selectBefore(PHP_EOL, $callback, included: $includeEol)
            : $this->selectAll($callback);
    }

    public function selectLastLine(Closure $callback, bool $includeEol = false): static
    {
        return str_contains($this->value, PHP_EOL)
            ? $this->selectAfter(PHP_EOL, $callback, last: true, included: $includeEol)
            : $this->selectAll($callback);
    }

    public function prependLine(string $content, bool $keepIndent = false): static
    {
        $lineJump = $this->value ? PHP_EOL : '';

        return $this->selectFirstLine(function (Variant $variant) use ($lineJump, $content, $keepIndent) {
            $indent = $keepIndent ? $variant->getLeftWhitespace() : '';

            return $variant->prepend("$indent$content$lineJump");
        });
    }

    public function appendLine(string $content, bool $keepIndent = false): static
    {
        $lineJump = $this->value ? PHP_EOL : '';

        return $this->selectLastLine(function (Variant $variant) use ($lineJump, $content, $keepIndent) {
            $indent = $keepIndent ? $variant->getLeftWhitespace() : '';

            return $variant->append("$lineJump$indent$content");
        });
    }

    public function addBeforeLine(string $search, string $line, bool $keepIndent = false): static
    {
        return $this->selectLine(
            $search,
            fn (Variant $variant) => $variant->prependLine($line, $keepIndent),
        );
    }

    public function addBeforeLinePattern(string $pattern, string $line, bool $keepIndent = false): static
    {
        return $this->selectLinePattern(
            $pattern,
            fn (Variant $variant) => $variant->prependLine($line, $keepIndent),
        );
    }

    public function addAfterLine(string $search, string $line, bool $keepIndent = false): static
    {
        return $this->selectLine(
            $search,
            fn (Variant $variant) => $variant->appendLine($line, $keepIndent)
        );
    }

    public function addAfterLinePattern(string $pattern, string $line, bool $keepIndent = false): static
    {
        return $this->selectLinePattern(
            $pattern,
            fn (Variant $variant) => $variant->appendLine($line, $keepIndent)
        );
    }

    public function deleteLine(string $search, int $limit = -1): static
    {
        $placeholder = $this->getRandomPlaceholder();
        $overrideCallback = fn (Variant $variant) => $variant->override($placeholder);

        return $this->selectLine($search, $overrideCallback, $limit)
            ->deletePlaceholderLines($placeholder);
    }

    public function deleteLinePattern(string $pattern, int $limit = -1): static
    {
        $placeholder = $this->getRandomPlaceholder();
        $overrideCallback = fn (Variant $variant) => $variant->override($placeholder);

        return $this->selectLinePattern($pattern, $overrideCallback, $limit)
            ->deletePlaceholderLines($placeholder);
    }

    public function deleteFirstLine(): static
    {
        return $this->selectFirstLine(
            callback: fn (Variant $variant) => $variant->empty(),
            includeEol: true,
        );
    }

    public function deleteLastLine(): static
    {
        return $this->selectLastLine(
            callback: fn (Variant $variant) => $variant->empty(),
            includeEol: true,
        );
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
