<?php

namespace Lorisleiva\Vary;

use Closure;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Traits\Tappable;

class Variant
{
    use Conditionable, Macroable, Tappable;

    protected string $value;
    protected ?string $path;

    public function __construct(string $value, ?string $path = null)
    {
        $this->value = $value;
        $this->path = $path;
    }

    public function getCurrentPath(): ?string
    {
        return $this->path;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function new(string $value): static
    {
        return new static($value, $this->path);
    }

    public function save(?string $path = null, int $flags = 0): bool
    {
        if (! $path = $path ?? $this->path) {
            throw new Exception('Path not given');
        }

        return file_put_contents($path, $this->value, $flags) !== false;
    }

    // TODO: Organise in traits.
    public function replace(string|array $search, string|array $replace): static
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

    public function replaceFirst(string $search, string $replace): static
    {
        return $this->new(Str::replaceFirst($search, $replace, $this->value));
    }

    public function replaceLast(string $search, string $replace): static
    {
        return $this->new(Str::replaceLast($search, $replace, $this->value));
    }

    public function replaceMatches(string $pattern, Closure|string $replace, int $limit = -1): static
    {
        if ($replace instanceof Closure) {
            return $this->new(preg_replace_callback($pattern, $replace, $this->value, $limit));
        }

        return $this->new(preg_replace($pattern, $replace, $this->value, $limit));
    }

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
