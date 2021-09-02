<?php

namespace Lorisleiva\Vary\Concerns;

use Closure;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Vary\Variant;

trait ProvidesMutators
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

    public function prependAfterWhitespace(string $prefix): static
    {
        return $this->selectAfterWhitespace(
            fn (Variant $variant) => $variant->prepend($prefix),
        );
    }

    public function appendBeforeWhitespace(string $suffix): static
    {
        return $this->selectBeforeWhitespace(
            fn (Variant $variant) => $variant->append($suffix),
        );
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

    public function replacePattern(string $pattern, Closure | string $replace, int $limit = -1): static
    {
        if ($replace instanceof Closure) {
            return $this->new(preg_replace_callback($pattern, $replace, $this->value, $limit));
        }

        return $this->new(preg_replace($pattern, $replace, $this->value, $limit));
    }

    public function delete(string | array $search): static
    {
        $replace = is_array($search) ? array_pad([], count($search), '') : '';

        return $this->replace($search, $replace);
    }

    #[Pure] public function deleteFirst(string $search): static
    {
        return $this->new(Str::replaceFirst($search, '', $this->value));
    }

    #[Pure] public function deleteLast(string $search): static
    {
        return $this->new(Str::replaceLast($search, '', $this->value));
    }

    public function deletePattern(string $pattern, int $limit = -1): static
    {
        return $this->new(preg_replace($pattern, '', $this->value, $limit));
    }
}
