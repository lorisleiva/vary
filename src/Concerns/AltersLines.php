<?php

namespace Lorisleiva\Vary\Concerns;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Lorisleiva\Vary\Regex;
use Lorisleiva\Vary\Variant;

trait AltersLines
{
    public function addAfterExactLine(string $search, string $content, bool $keepIndent = false): static
    {
        return $this->addAfterLine($search, $content, $keepIndent, false, false);
    }

    public function addAfterLine(string $search, string $content, bool $keepIndent = false, bool $ignoreWhitespace = true, bool $allowWildcards = true): static
    {
        return $this->selectLine(
            search: $search,
            callback: fn (Variant $variant) => $variant->appendLine($content, $keepIndent),
            ignoreWhitespace: $ignoreWhitespace,
            allowWildcards: $allowWildcards,
        );
    }

    public function addAfterLineMatches(string $pattern, string $content, bool $keepIndent = false, int $limit = -1): static
    {
        return $this->selectLineMatches(
            pattern: $pattern,
            callback: fn (Variant $variant) => $variant->appendLine($content, $keepIndent),
            limit: $limit,
        );
    }

    public function addBeforeExactLine(string $search, string $content, bool $keepIndent = false): static
    {
        return $this->addBeforeLine($search, $content, $keepIndent, false, false);
    }

    public function addBeforeLine(string $search, string $content, bool $keepIndent = false, bool $ignoreWhitespace = true, bool $allowWildcards = true): static
    {
        return $this->selectLine(
            search: $search,
            callback: fn (Variant $variant) => $variant->prependLine($content, $keepIndent),
            ignoreWhitespace: $ignoreWhitespace,
            allowWildcards: $allowWildcards,
        );
    }

    public function addBeforeLineMatches(string $pattern, string $content, bool $keepIndent = false, int $limit = -1): static
    {
        return $this->selectLineMatches(
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

    public function cleanEmptyLines(int $consecutiveEmptyLinesAllowed = 1, bool $ignoreWhitespace = true, int $limit = -1): static
    {
        $pattern = sprintf(
            '#^(%2$s\r?\n){%1$s,}#m',
            $consecutiveEmptyLinesAllowed + 1,
            $ignoreWhitespace ? '\s*' : '',
        );

        return $this->replaceMatches($pattern, "\n", $limit);
    }

    public function deleteFirstLine(): static
    {
        return $this->selectFirstLineWithEol(fn (Variant $variant) => $variant->empty());
    }

    public function deleteLastLine(): static
    {
        return $this->selectLastLineWithEol(fn (Variant $variant) => $variant->empty());
    }

    public function deleteExactLine(string $search, int $limit = -1): static
    {
        return $this->deleteLine($search, $limit, false, false);
    }

    public function deleteExactLines(array $lines): static
    {
        return $this->deleteLines($lines, false, false);
    }

    public function deleteLine(string $search, int $limit = -1, bool $ignoreWhitespace = true, bool $allowWildcards = true): static
    {
        return $this->selectLineWithEol(
            search: $search,
            callback: fn (Variant $line) => $line->empty(),
            limit: $limit,
            ignoreWhitespace: $ignoreWhitespace,
            allowWildcards: $allowWildcards,
        );
    }

    public function deleteLineMatches(string $pattern, int $limit = -1): static
    {
        return $this->selectLineMatchesWithEol(
            pattern: $pattern,
            callback: fn (Variant $line) => $line->empty(),
            limit: $limit,
        );
    }

    public function deleteLines(array $lines, bool $ignoreWhitespace = true, bool $allowWildcards = true): static
    {
        return array_reduce(
            array: $lines,
            callback: fn (Variant $variant, string $line) => $variant->deleteLine(
                search: $line,
                ignoreWhitespace: $ignoreWhitespace,
                allowWildcards: $allowWildcards,
            ),
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

    public function getLastLine(bool $includeEol = false): string
    {
        if (! str_contains($this->value, PHP_EOL)) {
            return $this->value;
        }

        $line = Str::afterLast($this->value, PHP_EOL);

        return $includeEol ? PHP_EOL . $line : $line;
    }

    public function getLastLineWithEol(): string
    {
        return $this->getLastLine(true);
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

    public function selectAllLines(Closure $callback, bool $includeEol = false): static
    {
        return $this->selectLineMatches('.*', $callback, includeEol: $includeEol);
    }

    public function selectAllLinesWithEol(Closure $callback): static
    {
        return $this->selectAllLines($callback, includeEol: true);
    }

    public function selectExactLine(string $search, Closure $callback, int $limit = -1): static
    {
        return $this->selectLine($search, $callback, $limit, ignoreWhitespace: false, allowWildcards: false);
    }

    public function selectExactLineWithEol(string $search, Closure $callback, int $limit = -1): static
    {
        return $this->selectLine($search, $callback, $limit, includeEol: true, ignoreWhitespace: false, allowWildcards: false);
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

    public function selectLine(string $search, Closure $callback, int $limit = -1, bool $includeEol = false, bool $ignoreWhitespace = true, bool $allowWildcards = true): static
    {
        $pattern = Regex::getWildcardLinePattern($search, $includeEol, $ignoreWhitespace, $allowWildcards);

        return $this->selectMatches($pattern, $callback, null, $limit);
    }

    public function selectLineMatches(string $pattern, Closure $callback, int $limit = -1, bool $includeEol = false, string $delimiter = '#'): static
    {
        $pattern = Regex::getLinePattern($pattern, $includeEol, $delimiter);

        return $this->selectMatches($pattern, $callback, null, $limit);
    }

    public function selectLineMatchesWithEol(string $pattern, Closure $callback, int $limit = -1, string $delimiter = '#'): static
    {
        return $this->selectLineMatches($pattern, $callback, $limit, true, $delimiter);
    }

    public function selectLineWithEol(string $search, Closure $callback, int $limit = -1, bool $ignoreWhitespace = true, bool $allowWildcards = true): static
    {
        return $this->selectLine($search, $callback, $limit, true, $ignoreWhitespace, $allowWildcards);
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
