<?php

namespace Lorisleiva\Vary\Concerns;

use Closure;
use Illuminate\Support\Str;
use Lorisleiva\Vary\Variant;

trait HandlesFragments
{
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

    public function match(string $pattern, Closure $callback, ?Closure $replace = null, int $limit = -1): static
    {
        $replace = $replace ?? fn (array $matches, Closure $next) => $next($matches[0]);

        $next = fn (string $fragment) => $this->fragment($fragment, $callback);
        $newReplace = fn ($matches) => $replace($matches, $next);

        return $this->replaceMatches($pattern, $newReplace, $limit);
    }

    public function matchFirstGroup(string $pattern, Closure $callback, int $limit = -1): static
    {
        $replace = function (array $matches, Closure $next) {
            $before = Str::before($matches[0], $matches[1]);
            $after = Str::after($matches[0], $matches[1]);

            return $before . $next($matches[1]) . $after;
        };

        return $this->match($pattern, $callback, $replace, $limit);
    }

    public function matchLine(string $pattern, Closure $callback, int $limit = -1): static
    {
        return $this->match("/^.*$pattern.*$/m", $callback, null, $limit);
    }
}
