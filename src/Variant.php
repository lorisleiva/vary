<?php

namespace Lorisleiva\Vary;

use Closure;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Traits\Tappable;
use JetBrains\PhpStorm\NoReturn;
use JetBrains\PhpStorm\Pure;

class Variant
{
    use Conditionable;
    use Macroable;
    use Tappable;

    protected string $value;
    protected ?string $path;

    public function __construct(string $value, ?string $path = null)
    {
        $this->value = $value;
        $this->path = $path;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function toString(): string
    {
        return $this->value;
    }

    #[Pure] public function __toString(): string
    {
        return $this->toString();
    }

    #[Pure] public function new(string $value): static
    {
        return new static($value, $this->path);
    }

    public function save(?string $path = null, int $flags = 0): static
    {
        if (! $path = $path ?? $this->path) {
            throw new Exception('Path not given');
        }

        file_put_contents($path, $this->value, $flags) !== false;

        return $this;
    }

    public function saveAs(string $path, int $flags = 0): static
    {
        return $this->save($path, $flags);
    }

    public function dump(): static
    {
        VarDumper::dump($this->value);

        return $this;
    }

    #[NoReturn] public function dd(): void
    {
        $this->dump();

        exit(1);
    }

    // TODO: Organise in traits.
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
