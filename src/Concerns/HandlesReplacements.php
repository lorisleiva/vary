<?php

namespace Lorisleiva\Vary\Concerns;

use Closure;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;

trait HandlesReplacements
{
    public function replace(string | array $search, string | array $replace): static
    {
        return $this->new(Str::replace($search, $replace, $this->value));
    }

    public function replaceAll(array $replacements): static
    {
        return $this->replace(array_keys($replacements), array_values($replacements));
    }

    public function replaceSequentially(string $search, array $replace): static
    {
        return $this->new(Str::replaceArray($search, $replace, $this->value));
    }

    #[Pure] public function replaceFirst(string $search, string $replace): static
    {
        return $this->new(Str::replaceFirst($search, $replace, $this->value));
    }

    #[Pure] public function replaceLast(string $search, string $replace): static
    {
        return $this->new(Str::replaceLast($search, $replace, $this->value));
    }

    public function replaceMatches(string $pattern, Closure | string $replace, int $limit = -1): static
    {
        if ($replace instanceof Closure) {
            return $this->new(preg_replace_callback($pattern, $replace, $this->value, $limit));
        }

        return $this->new(preg_replace($pattern, $replace, $this->value, $limit));
    }

    #[Pure] public function empty(): static
    {
        return $this->new('');
    }
}
