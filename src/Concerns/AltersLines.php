<?php

namespace Lorisleiva\Vary\Concerns;

use Illuminate\Support\Arr;

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
        $safeSearch = preg_quote($search, '/');
        $replace = function (array $matches) use ($line, $keepIndent) {
            $indent = $keepIndent ? $matches[1] : '';

            return $matches[0] . "\n$indent$line";
        };

        return $this->replaceMatches("/^(\s*)$safeSearch\s*$/m", $replace);
    }

    public function addLineBefore(string $search, string $line, bool $keepIndent = false): static
    {
        $safeSearch = preg_quote($search, '/');
        $replace = function (array $matches) use ($line, $keepIndent) {
            $indent = $keepIndent ? $matches[1] : '';

            return  "$indent$line\n" . $matches[0];
        };

        return $this->replaceMatches("/^(\s*)$safeSearch\s*$/m", $replace);
    }
}
