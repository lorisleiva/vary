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

        return $this->selectMatches("/$safeSearch/", $callback, limit: $limit);
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

    public function selectMatches(string $pattern, Closure $callback, ?Closure $replace = null, int $limit = -1): static
    {
        $replace = $replace ?? function (array $matches, Closure $next) {
            if (! isset($matches[1])) {
                return $next($matches[0]);
            }

            $before = Str::before($matches[0], $matches[1]);
            $after = Str::after($matches[0], $matches[1]);

            return $before . $next($matches[1]) . $after;
        };

        $next = fn (string $fragment) => $this->evaluateFragment($fragment, $callback);
        $newReplace = fn ($matches) => $replace($matches, $next);

        return $this->replaceMatches($pattern, $newReplace, $limit);
    }

    protected function evaluateFragment(string $oldValue, Closure $callback): string
    {
        $newValue = $callback(new static($oldValue));

        return $newValue instanceof Variant ? $newValue->toString() : $newValue;
    }
}
