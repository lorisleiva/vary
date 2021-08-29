<?php

namespace Lorisleiva\Vary\Concerns;

trait HandlesMustaches
{
    public function mustache(string $variable, string $value, int $limit = -1): static
    {
        $safeVariable = preg_quote($variable, '/');

        return $this->new(preg_replace("/{{\s*$safeVariable\s*}}/", $value, $this->value, $limit));
    }

    public function mustacheAll(array $replacements): static
    {
        $value = $this->value;

        foreach ($replacements as $variable => $variableValue) {
            $safeVariable = preg_quote($variable, '/');

            $value = preg_replace("/{{\s*$safeVariable\s*}}/", $variableValue, $value);
        }

        return $this->new($value);
    }
}
