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

    public function selectFirstLine(Closure $callback, bool $includeEol = false): static
    {
        return str_contains($this->value, PHP_EOL)
            ? $this->selectBefore(PHP_EOL, $callback, included: $includeEol)
            : $this->selectAll($callback);
    }

    public function selectFirstLineWithEol(Closure $callback): static
    {
        return $this->selectFirstLine($callback, includeEol: true);
    }

    public function selectLastLine(Closure $callback, bool $includeEol = false): static
    {
        return str_contains($this->value, PHP_EOL)
            ? $this->selectAfter(PHP_EOL, $callback, last: true, included: $includeEol)
            : $this->selectAll($callback);
    }

    public function selectLastLineWithEol(Closure $callback): static
    {
        return $this->selectLastLine($callback, includeEol: true);
    }

    public function selectAllLines(Closure $callback): static
    {
        return $this->selectLinePattern('/^.*$/', $callback);
    }

    public function selectLinePattern(string $pattern, Closure $callback, ?Closure $replace = null, int $limit = -1): static
    {
        $pattern = $this->ensureMultipleRegex($pattern);

        return $this->selectPattern($pattern, $callback, $replace, $limit);
    }

    public function prependLine(string $content, bool $keepIndent = false): static
    {
        $lineJump = $this->value ? PHP_EOL : '';

        return $this->selectFirstLine(function (Variant $variant) use ($lineJump, $content, $keepIndent) {
            if ($keepIndent) {
                $indent = $variant->getLeftWhitespace();
                $content = (new static($content))
                    ->selectAllLines(fn (Variant $line) => $line->prepend($indent))
                    ->toString();
            }

            return $variant->prepend("$content$lineJump");
        });
    }

    public function appendLine(string $content, bool $keepIndent = false): static
    {
        $lineJump = $this->value ? PHP_EOL : '';

        return $this->selectLastLine(function (Variant $variant) use ($lineJump, $content, $keepIndent) {
            if ($keepIndent) {
                $indent = $variant->getLeftWhitespace();
                $content = (new static($content))
                    ->selectAllLines(fn (Variant $line) => $line->prepend($indent))
                    ->toString();
            }

            return $variant->append("$lineJump$content");
        });
    }

    public function addBeforeLine(string $search, string $content, bool $keepIndent = false, bool $ignoreWhitespace = true): static
    {
        return $this->selectLine(
            search: $search,
            callback: fn (Variant $variant) => $variant->prependLine($content, $keepIndent),
            ignoreWhitespace: $ignoreWhitespace,
        );
    }

    public function addBeforeLinePattern(string $pattern, string $content, bool $keepIndent = false, int $limit = -1): static
    {
        return $this->selectLinePattern(
            pattern: $pattern,
            callback: fn (Variant $variant) => $variant->prependLine($content, $keepIndent),
            limit: $limit,
        );
    }

    public function addAfterLine(string $search, string $content, bool $keepIndent = false): static
    {
        return $this->selectLine(
            $search,
            fn (Variant $variant) => $variant->appendLine($content, $keepIndent)
        );
    }

    public function addAfterLinePattern(string $pattern, string $content, bool $keepIndent = false, int $limit = -1): static
    {
        return $this->selectLinePattern(
            pattern: $pattern,
            callback: fn (Variant $variant) => $variant->appendLine($content, $keepIndent),
            limit: $limit,
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

        return $this->selectLinePattern($pattern, $overrideCallback, limit: $limit)
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

    protected function ensureMultipleRegex(string $pattern): string
    {
        if (! $delimiter = $pattern[0] ?? null) {
            return $pattern;
        }

        return Str::of($pattern)->afterLast($delimiter)->contains('m')
            ? $pattern
            : ($pattern . 'm');
    }
}
