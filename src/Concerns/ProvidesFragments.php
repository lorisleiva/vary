<?php

namespace Lorisleiva\Vary\Concerns;

use Closure;
use Illuminate\Support\Str;
use Lorisleiva\Vary\Variant;

trait ProvidesFragments
{
    public function select(string $search, Closure $callback, int $limit = -1): static
    {
        $safeSearch = preg_quote($search, '/');

        return $this->selectPattern("/$safeSearch/", $callback, limit: $limit);
    }

    public function selectAfter(string $search, Closure $callback, bool $last = false, bool $included = false): static
    {
        $oldValue = $last
            ? Str::afterLast($this->value, $search)
            : Str::after($this->value, $search);

        if ($included && $oldValue !== $this->value) {
            $oldValue = $search . $oldValue;
        }

        $newValue = $this->evaluateFragment($oldValue, $callback);

        return $oldValue === ''
            ? $this->append($newValue)
            : $this->replaceLast($oldValue, $newValue);
    }

    public function selectAfterIncluded(string $search, Closure $callback): static
    {
        return $this->selectAfter($search, $callback, included: true);
    }

    public function selectAfterLast(string $search, Closure $callback): static
    {
        return $this->selectAfter($search, $callback, last: true);
    }

    public function selectAfterLastIncluded(string $search, Closure $callback): static
    {
        return $this->selectAfter($search, $callback, last: true, included: true);
    }

    public function selectAll(Closure $callback): static
    {
        $newValue = $this->evaluateFragment($this->value, $callback);

        return $this->new($newValue);
    }

    public function selectBefore(string $search, Closure $callback, bool $last = false, bool $included = false): static
    {
        $oldValue = $last
            ? Str::beforeLast($this->value, $search)
            : Str::before($this->value, $search);

        if ($included && $oldValue !== $this->value) {
            $oldValue = $oldValue . $search;
        }

        $newValue = $this->evaluateFragment($oldValue, $callback);

        return $oldValue === ''
            ? $this->prepend($newValue)
            : $this->replaceFirst($oldValue, $newValue);
    }

    public function selectBeforeIncluded(string $search, Closure $callback): static
    {
        return $this->selectBefore($search, $callback, included: true);
    }

    public function selectBeforeLast(string $search, Closure $callback): static
    {
        return $this->selectBefore($search, $callback, last: true);
    }

    public function selectBeforeLastIncluded(string $search, Closure $callback): static
    {
        return $this->selectBefore($search, $callback, last: true, included: true);
    }

    public function selectBetween(string $from, string $to, Closure $callback, bool $fromLast = false, bool $fromIncluded = false, bool $toLast = false, bool $toIncluded = false): static
    {
        return $this->selectAfter(
            search: $from,
            callback: fn (Variant $variant) => $variant->selectBefore(
                search: $to,
                callback: $callback,
                last: $toLast,
                included: $toIncluded,
            ),
            last: $fromLast,
            included: $fromIncluded,
        );
    }

    public function selectBetweenFirstAndFirst(string $from, string $to, Closure $callback): static
    {
        return $this->selectBetween($from, $to, $callback);
    }

    public function selectBetweenFirstAndFirstIncluded(string $from, string $to, Closure $callback): static
    {
        return $this->selectBetween($from, $to, $callback, fromIncluded: true, toIncluded: true);
    }

    public function selectBetweenFirstAndLast(string $from, string $to, Closure $callback): static
    {
        return $this->selectBetween($from, $to, $callback, toLast: true);
    }

    public function selectBetweenFirstAndLastIncluded(string $from, string $to, Closure $callback): static
    {
        return $this->selectBetween($from, $to, $callback, fromIncluded: true, toLast: true, toIncluded: true);
    }

    public function selectBetweenLastAndFirst(string $from, string $to, Closure $callback): static
    {
        return $this->selectBetween($from, $to, $callback, fromLast: true);
    }

    public function selectBetweenLastAndFirstIncluded(string $from, string $to, Closure $callback): static
    {
        return $this->selectBetween($from, $to, $callback, fromLast: true, fromIncluded: true, toIncluded: true);
    }

    public function selectBetweenLastAndLast(string $from, string $to, Closure $callback): static
    {
        return $this->selectBetween($from, $to, $callback, fromLast: true, toLast: true);
    }

    public function selectBetweenLastAndLastIncluded(string $from, string $to, Closure $callback): static
    {
        return $this->selectBetween($from, $to, $callback, fromLast: true, fromIncluded: true, toLast: true, toIncluded: true);
    }

    public function selectPattern(string $pattern, Closure $callback, ?Closure $replace = null, int $limit = -1): static
    {
        $replace = $replace ?? fn (array $matches, Closure $next) => $next($matches[0]);

        $next = fn (string $fragment) => $this->evaluateFragment($fragment, $callback);
        $newReplace = fn ($matches) => $replace($matches, $next);

        return $this->replacePattern($pattern, $newReplace, $limit);
    }

    public function selectPatternFirstGroup(string $pattern, Closure $callback, int $limit = -1): static
    {
        $replace = function (array $matches, Closure $next) {
            $before = Str::before($matches[0], $matches[1]);
            $after = Str::after($matches[0], $matches[1]);

            return $before . $next($matches[1]) . $after;
        };

        return $this->selectPattern($pattern, $callback, $replace, $limit);
    }

    public function tap(Closure $callback): static
    {
        return $this->selectAll($callback);
    }

    protected function evaluateFragment(string $oldValue, Closure $callback): string
    {
        $newValue = $callback(new static($oldValue));

        return $newValue instanceof Variant ? $newValue->toString() : $newValue;
    }
}
