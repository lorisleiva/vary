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
}
