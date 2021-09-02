<?php

namespace Lorisleiva\Vary\Concerns;

trait AltersMustaches
{
    public function replaceMustache(string $variable, string $value, int $limit = -1): static
    {
        $safeVariable = preg_quote($variable, '/');

        return $this->replacePattern("/{{\s*$safeVariable\s*}}/", $value, $limit);
    }

    public function replaceAllMustaches(array $replacements): static
    {
        $variant = $this;

        foreach ($replacements as $variable => $value) {
            $variant = $variant->replaceMustache($variable, $value);
        }

        return $variant;
    }
}
