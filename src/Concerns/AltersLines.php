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

    public function appendLine(string $line, bool $keepIndent = false): static
    {
        $lineJump = $this->value ? "\n" : '';
        $indent = '';

        if ($keepIndent) {
            $lastLine = Arr::last(explode("\n", $this->value));
            $indent = $this->getIndentFromLine($lastLine);
        }

        return $this->new($this->value . "$lineJump$indent$line");
    }

    public function prependLine(string $line, bool $keepIndent = false): static
    {
        $lineJump = $this->value ? "\n" : '';
        $indent = '';

        if ($keepIndent) {
            $lastLine = Arr::first(explode("\n", $this->value));
            $indent = $this->getIndentFromLine($lastLine);
        }

        return $this->new("$indent$line$lineJump" . $this->value);
    }
}
