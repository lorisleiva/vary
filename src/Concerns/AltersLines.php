<?php

namespace Lorisleiva\Vary\Concerns;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Lorisleiva\Vary\Variant;

trait AltersLines
{
    public function addAfterLine(string $search, string $content, bool $keepIndent = false, bool $ignoreWhitespace = true): static
    {
        return $this->selectLine(
            search: $search,
            callback: fn (Variant $variant) => $variant->appendLine($content, $keepIndent),
            ignoreWhitespace: $ignoreWhitespace,
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

    public function appendLineInPattern(string $pattern, string $content, bool $keepIndent = false, ?Closure $replace = null, int $limit = -1): static
    {
        return $this->selectMatches(
            pattern: $pattern,
            callback: fn (Variant $variant) => $variant->appendLine($content, $keepIndent),
            replace: $replace,
            limit: $limit,
        );
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

    public function deleteLine(string $search, int $limit = -1, bool $ignoreWhitespace = true): static
    {
        $spaces = '[^\S\r\n]*';
        $safeSearch = preg_quote($search, '/');
        $safeSearch = $ignoreWhitespace ? "^{$spaces}{$safeSearch}{$spaces}$" : "^{$safeSearch}$";

        return $this->selectMatches(
            pattern: "/($safeSearch\n|\n$safeSearch|$safeSearch)/m",
            callback: fn (Variant $line) => $line->empty(),
            limit: $limit,
        );
    }

    public function deleteLinePattern(string $pattern, int $limit = -1): static
    {
        return $this->selectMatches(
            pattern: "/(^$pattern$\n|\n^$pattern$|^$pattern$)/m",
            callback: fn (Variant $line) => $line->empty(),
            limit: $limit,
        );
    }

    public function deleteLines(array $lines, bool $ignoreWhitespace = true): static
    {
        return array_reduce(
            array: $lines,
            callback: fn (Variant $variant, string $line) => $variant->deleteLine($line, $ignoreWhitespace),
            initial: $this,
        );
    }

    public function getAllLines(bool $includeEol = false): array
    {
        $lines = explode(PHP_EOL, $this->value);

        if ($includeEol) {
            $lastLine = array_pop($lines);
            $lines = array_map(fn (string $line) => $line . PHP_EOL, $lines);
            $lines[] = $lastLine;
        }

        return $lines;
    }

    public function getAllLinesWithEol(): array
    {
        return $this->getAllLines(true);
    }

    public function getFirstLine(bool $includeEol = false): string
    {
        if (! str_contains($this->value, PHP_EOL)) {
            return $this->value;
        }

        $line = Str::before($this->value, PHP_EOL);

        return $includeEol ? $line . PHP_EOL : $line;
    }

    public function getFirstLineWithEol(): string
    {
        return $this->getFirstLine(true);
    }

    public function getLastLine(): string
    {
        if (! str_contains($this->value, PHP_EOL)) {
            return $this->value;
        }

        return Str::afterLast($this->value, PHP_EOL);
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

    public function prependLineInPattern(string $pattern, string $content, bool $keepIndent = false, ?Closure $replace = null, int $limit = -1): static
    {
        return $this->selectMatches(
            pattern: $pattern,
            callback: fn (Variant $variant) => $variant->prependLine($content, $keepIndent),
            replace: $replace,
            limit: $limit,
        );
    }

    public function selectAllLines(Closure $callback, bool $includeEol = false): static
    {
        return $this->selectLinePattern('.*', $callback, includeEol: $includeEol);
    }

    public function selectAllLinesWithEol(Closure $callback): static
    {
        return $this->selectAllLines($callback, includeEol: true);
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
            : $this->pipe($callback);
    }

    public function selectFirstLineWithEol(Closure $callback): static
    {
        return $this->selectFirstLine($callback, includeEol: true);
    }

    public function selectLastLine(Closure $callback, bool $includeEol = false): static
    {
        return str_contains($this->value, PHP_EOL)
            ? $this->selectAfter(PHP_EOL, $callback, last: true, included: $includeEol)
            : $this->pipe($callback);
    }

    public function selectLastLineWithEol(Closure $callback): static
    {
        return $this->selectLastLine($callback, includeEol: true);
    }

    public function selectLine(string $search, Closure $callback, int $limit = -1, bool $includeEol = false, bool $ignoreWhitespace = true): static
    {
        $safeSearch = preg_quote($search, '/');
        $spaces = '[^\S\r\n]*';
        $regex = $ignoreWhitespace
            ? ($includeEol ? "/^{$spaces}{$safeSearch}{$spaces}$\n?/m" : "/^{$spaces}{$safeSearch}{$spaces}$/m")
            : ($includeEol ? "/^{$safeSearch}$\n?/m" : "/^{$safeSearch}$/m");

        return $this->selectMatches($regex, $callback, null, $limit);
    }

    public function selectLinePattern(string $pattern, Closure $callback, ?Closure $replace = null, int $limit = -1, bool $includeEol = false): static
    {
        $pattern = $includeEol ? "/^$pattern$\n?/m" : "/^$pattern$/m";

        return $this->selectMatches($pattern, $callback, $replace, $limit);
    }

    public function selectLinePatternWithEol(string $pattern, Closure $callback, ?Closure $replace = null, int $limit = -1): static
    {
        return $this->selectLinePattern($pattern, $callback, $replace, $limit, includeEol: true);
    }

    public function selectLineWithEol(string $search, Closure $callback, int $limit = -1): static
    {
        return $this->selectLine($search, $callback, $limit, includeEol: true);
    }

    public function sortLines(?Closure $callback = null): static
    {
        $lines = $this->getAllLines();

        if ($hasTrailingEol = Arr::last($lines) === '') {
            array_pop($lines);
        }

        $sortedLines = Arr::sort($lines, $callback);

        if ($hasTrailingEol) {
            $sortedLines[] = '';
        }

        return $this->new(join(PHP_EOL, $sortedLines));
    }

    public function sortLinesByLength(): static
    {
        return $this->sortLines(fn (string $value) => strlen($value));
    }
}
