<?php

namespace Lorisleiva\Vary\Concerns;

use Illuminate\Support\Arr;
use Lorisleiva\Vary\Variant;

trait AltersLines
{
    protected function getIndentFromLine(string $line): string
    {
        preg_match('/^(\s*)/', $line, $matches);

        return $matches[1] ?? '';
    }

    public function getFirstLine(): string
    {
        return Arr::first(explode("\n", $this->value));
    }

    public function getLastLine(): string
    {
        return Arr::last(explode("\n", $this->value));
    }

    public function appendLine(string $line, bool $keepIndent = false): static
    {
        $lineJump = $this->value ? "\n" : '';
        $indent = $keepIndent ? $this->getIndentFromLine($this->getLastLine()) : '';

        return $this->new($this->value . "$lineJump$indent$line");
    }

    public function prependLine(string $line, bool $keepIndent = false): static
    {
        $lineJump = $this->value ? "\n" : '';
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
}
