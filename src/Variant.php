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
use Lorisleiva\Vary\Concerns\AltersMethods;
use Lorisleiva\Vary\Concerns\AltersProperties;
use Lorisleiva\Vary\Concerns\HandlesMustaches;
use Lorisleiva\Vary\Concerns\HandlesReplacements;
use Symfony\Component\VarDumper\VarDumper;

class Variant
{
    use Conditionable;
    use Macroable;
    use Tappable;
    use HandlesReplacements;
    use HandlesMustaches;
    use AltersProperties;
    use AltersMethods;

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

    protected function fragment(string $oldValue, Closure $callback): string
    {
        $newValue = $callback(new static($oldValue));

        return $newValue instanceof Variant ? $newValue->toString() : $newValue;
    }

    public function before(string $search, Closure $callback, bool $last = false, bool $included = false): static
    {
        $oldValue = $last
            ? Str::beforeLast($this->value, $search)
            : Str::before($this->value, $search);

        if ($included) {
            $oldValue = $oldValue . $search;
        }

        $newValue = $this->fragment($oldValue, $callback);

        return $this->replaceFirst($oldValue, $newValue);
    }

    public function beforeLast(string $search, Closure $callback): static
    {
        return $this->before($search, $callback, last: true);
    }

    public function beforeIncluded(string $search, Closure $callback): static
    {
        return $this->before($search, $callback, included: true);
    }

    public function beforeLastIncluded(string $search, Closure $callback): static
    {
        return $this->before($search, $callback, last: true, included: true);
    }

    public function after(string $search, Closure $callback, bool $last = false, bool $included = false): static
    {
        $oldValue = $last
            ? Str::afterLast($this->value, $search)
            : Str::after($this->value, $search);

        if ($included) {
            $oldValue = $search . $oldValue;
        }

        $newValue = $this->fragment($oldValue, $callback);

        return $this->replaceLast($oldValue, $newValue);
    }

    public function afterLast(string $search, Closure $callback): static
    {
        return $this->after($search, $callback, last: true);
    }

    public function afterIncluded(string $search, Closure $callback): static
    {
        return $this->after($search, $callback, included: true);
    }

    public function afterLastIncluded(string $search, Closure $callback): static
    {
        return $this->after($search, $callback, last: true, included: true);
    }

    public function between(string $from, string $to, Closure $callback, bool $fromLast = false, bool $fromIncluded = false, bool $toLast = false, bool $toIncluded = false): static
    {
        return $this->after(
            search: $from,
            callback: fn (Variant $variant) => $variant->before(
                search: $to,
                callback: $callback,
                last: $toLast,
                included: $toIncluded,
            ),
            last: $fromLast,
            included: $fromIncluded,
        );
    }

    public function betweenFirstAndFirst(string $from, string $to, Closure $callback): static
    {
        return $this->between($from, $to, $callback);
    }

    public function betweenFirstAndFirstIncluded(string $from, string $to, Closure $callback): static
    {
        return $this->between($from, $to, $callback, fromIncluded: true, toIncluded: true);
    }

    public function betweenFirstAndLast(string $from, string $to, Closure $callback): static
    {
        return $this->between($from, $to, $callback, toLast: true);
    }

    public function betweenFirstAndLastIncluded(string $from, string $to, Closure $callback): static
    {
        return $this->between($from, $to, $callback, fromIncluded: true, toLast: true, toIncluded: true);
    }

    public function betweenLastAndFirst(string $from, string $to, Closure $callback): static
    {
        return $this->between($from, $to, $callback, fromLast: true);
    }

    public function betweenLastAndFirstIncluded(string $from, string $to, Closure $callback): static
    {
        return $this->between($from, $to, $callback, fromLast: true, fromIncluded: true, toIncluded: true);
    }

    public function betweenLastAndLast(string $from, string $to, Closure $callback): static
    {
        return $this->between($from, $to, $callback, fromLast: true, toLast: true);
    }

    public function betweenLastAndLastIncluded(string $from, string $to, Closure $callback): static
    {
        return $this->between($from, $to, $callback, fromLast: true, fromIncluded: true, toLast: true, toIncluded: true);
    }
}
