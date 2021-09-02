<?php

namespace Lorisleiva\Vary\Concerns;

use Closure;
use Illuminate\Support\Str;
use Lorisleiva\Vary\Variant;

trait AltersLines
{
    public function selectLine(string $search, Closure $callback, int $limit = -1, bool $includeEol = false): static
    {
        $safeSearch = preg_quote($search, '/');
        $regex = $includeEol ? "/^\s*$safeSearch\s*$\n?/m" : "/^\s*$safeSearch\s*$/m";

        return $this->selectPattern($regex, $callback, null, $limit);
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
            : $this->tap($callback);
    }

    public function selectLastLine(Closure $callback, bool $includeEol = false): static
    {
        return str_contains($this->value, PHP_EOL)
            ? $this->selectAfter(PHP_EOL, $callback, last: true, included: $includeEol)
            : $this->tap($callback);
    }

    public function prependLine(string $line, bool $keepIndent = false): static
    {
        $lineJump = $this->value ? PHP_EOL : '';

        return $this->selectFirstLine(function (Variant $variant) use ($lineJump, $line, $keepIndent) {
            $indent = $keepIndent ? $variant->getLeftWhitespace() : '';

            return $variant->prepend("$indent$line$lineJump");
        });
    }

    public function appendLine(string $line, bool $keepIndent = false): static
    {
        $lineJump = $this->value ? PHP_EOL : '';

        return $this->selectLastLine(function (Variant $variant) use ($lineJump, $line, $keepIndent) {
            $indent = $keepIndent ? $variant->getLeftWhitespace() : '';

            return $variant->append("$lineJump$indent$line");
        });
    }

    public function addLineBefore(string $search, string $line, bool $keepIndent = false): static
    {
        return $this->selectLine(
            $search,
            fn (Variant $variant) => $variant->prependLine($line, $keepIndent),
        );
    }

    public function addLineBeforePattern(string $search, string $line, bool $keepIndent = false): static
    {
        return $this->selectLinePattern(
            $search,
            fn (Variant $variant) => $variant->prependLine($line, $keepIndent),
        );
    }

    public function addLineAfter(string $search, string $line, bool $keepIndent = false): static
    {
        return $this->selectLine(
            $search,
            fn (Variant $variant) => $variant->appendLine($line, $keepIndent)
        );
    }

    public function addLineAfterPattern(string $search, string $line, bool $keepIndent = false): static
    {
        return $this->selectLinePattern(
            $search,
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

    public function deleteLinePattern(string $search, int $limit = -1): static
    {
        $placeholder = $this->getRandomPlaceholder();
        $overrideCallback = fn (Variant $variant) => $variant->override($placeholder);

        return $this->selectLinePattern($search, $overrideCallback, $limit)
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
