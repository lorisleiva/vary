<?php

namespace Lorisleiva\Vary\Concerns;

use Closure;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;

trait HandlesReplacements
{
    #[Pure] public function empty(): static
    {
        return $this->new('');
    }

    #[Pure] public function override(string $content): static
    {
        return $this->new($content);
    }

    #[Pure] public function prepend(string $prefix): static
    {
        return $this->new($prefix . $this->value);
    }

    #[Pure] public function append(string $suffix): static
    {
        return $this->new($this->value . $suffix);
    }

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
}
