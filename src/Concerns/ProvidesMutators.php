<?php

namespace Lorisleiva\Vary\Concerns;

use Closure;
use Illuminate\Support\Str;
use Lorisleiva\Vary\Variant;

trait ProvidesMutators
{
    public function addAfter(string $search, string $content): static
    {
        return $this->replace($search, $search . $content);
    }

    public function addAfterFirst(string $search, string $content): static
    {
        return $this->replaceFirst($search, $search . $content);
    }

    public function addAfterLast(string $search, string $content): static
    {
        return $this->replaceLast($search, $search . $content);
    }

    public function addAfterMatches(string $pattern, string $content, int $limit = -1): static
    {
        return $this->selectMatches(
            pattern: $pattern,
            callback: fn (Variant $variant) => $variant->append($content),
            limit: $limit,
        );
    }

    public function addBefore(string $search, string $content): static
    {
        return $this->replace($search, $content . $search);
    }

    public function addBeforeFirst(string $search, string $content): static
    {
        return $this->replaceFirst($search, $content . $search);
    }

    public function addBeforeLast(string $search, string $content): static
    {
        return $this->replaceLast($search, $content . $search);
    }

    public function addBeforeMatches(string $pattern, string $content, int $limit = -1): static
    {
        return $this->selectMatches(
            pattern: $pattern,
            callback: fn (Variant $variant) => $variant->prepend($content),
            limit: $limit,
        );
    }

    public function after(string $search): static
    {
        return $this->new(Str::after($this->value, $search));
    }

    public function afterLast(string $search): static
    {
        return $this->new(Str::afterLast($this->value, $search));
    }

    public function append(string ...$values): static
    {
        return $this->new($this->value . implode('', $values));
    }

    public function ascii(string $language = 'en'): static
    {
        return $this->new(Str::ascii($this->value, $language));
    }

    public function basename(string $suffix = ''): static
    {
        return $this->new(basename($this->value, $suffix));
    }

    public function before(string $search): static
    {
        return $this->new(Str::before($this->value, $search));
    }

    public function beforeLast(string $search): static
    {
        return $this->new(Str::beforeLast($this->value, $search));
    }

    public function between(string $from, string $to): static
    {
        return $this->new(Str::between($this->value, $from, $to));
    }

    public function camel(): static
    {
        return $this->new(Str::camel($this->value));
    }

    public function classBasename(): static
    {
        return $this->new(class_basename($this->value));
    }

    public function delete(string|array $search): static
    {
        $replace = is_array($search) ? array_pad([], count($search), '') : '';

        return $this->replace($search, $replace);
    }

    public function deleteFirst(string $search): static
    {
        return $this->replaceFirst($search, '');
    }

    public function deleteLast(string $search): static
    {
        return $this->replaceLast($search, '');
    }

    public function deleteMatches(string $pattern, int $limit = -1): static
    {
        return $this->selectMatches(
            pattern: $pattern,
            callback: fn (Variant $variant) => $variant->empty(),
            limit: $limit,
        );
    }

    public function dirname(int $levels = 1): static
    {
        return $this->new(dirname($this->value, $levels));
    }

    public function empty(): static
    {
        return $this->new('');
    }

    public function emptyFragment(): static
    {
        $inside = $this->startsWith(PHP_EOL) && $this->endsWith(PHP_EOL);

        return $inside ? $this->override(PHP_EOL) : $this->empty();
    }

    public function finish(string $cap): static
    {
        return $this->new(Str::finish($this->value, $cap));
    }

    public function headline(): static
    {
        return $this->new(Str::headline($this->value));
    }

    public function kebab(): static
    {
        return $this->new(Str::kebab($this->value));
    }

    public function limit(int $limit = 100, string $end = '...'): static
    {
        return $this->new(Str::limit($this->value, $limit, $end));
    }

    public function lower(): static
    {
        return $this->new(Str::lower($this->value));
    }

    public function ltrim(?string $characters = null): static
    {
        return $this->new(ltrim(...array_merge([$this->value], func_get_args())));
    }

    public function markdown(array $options = []): static
    {
        return $this->new(Str::markdown($this->value, $options));
    }

    public function mask(string $character, int $index, ?int $length = null, string $encoding = 'UTF-8'): static
    {
        return $this->new(Str::mask($this->value, $character, $index, $length, $encoding));
    }

    public function match(string $pattern): static
    {
        return $this->new(Str::match($pattern, $this->value));
    }

    public function override(string $content): static
    {
        return $this->new($content);
    }

    public function padBoth(int $length, string $pad = ' '): static
    {
        return $this->new(Str::padBoth($this->value, $length, $pad));
    }

    public function padLeft(int $length, string $pad = ' '): static
    {
        return $this->new(Str::padLeft($this->value, $length, $pad));
    }

    public function padRight(int $length, string $pad = ' '): static
    {
        return $this->new(Str::padRight($this->value, $length, $pad));
    }

    public function pipe(callable $callback): static
    {
        return $this->new(call_user_func($callback, $this));
    }

    public function plural(int $count = 2): static
    {
        return $this->new(Str::plural($this->value, $count));
    }

    public function pluralStudly(int $count = 2): static
    {
        return $this->new(Str::pluralStudly($this->value, $count));
    }

    public function prepend(string ...$values): static
    {
        return $this->new(implode('', $values) . $this->value);
    }

    public function remove(string|array $search, bool $caseSensitive = true): static
    {
        return $this->new(Str::remove($search, $this->value, $caseSensitive));
    }

    public function repeat(int $times): static
    {
        return $this->new(Str::repeat($this->value, $times));
    }

    public function replace(string|array $search, string|array $replace): static
    {
        return $this->new(Str::replace($search, $replace, $this->value));
    }

    public function replaceAll(array $replacements): static
    {
        return $this->replace(array_keys($replacements), array_values($replacements));
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
        return $this->new(Str::of($this->value)->replaceMatches($pattern, $replace, $limit));
    }

    public function replaceSequentially(string $search, array $replace): static
    {
        return $this->new(Str::replaceArray($search, $replace, $this->value));
    }

    public function reverse(): static
    {
        return $this->new(Str::reverse($this->value));
    }

    public function rtrim(?string $characters = null): static
    {
        return $this->new(rtrim(...array_merge([$this->value], func_get_args())));
    }

    public function singular(): static
    {
        return $this->new(Str::singular($this->value));
    }

    public function slug(string $separator = '-', ?string $language = 'en'): static
    {
        return $this->new(Str::slug($this->value, $separator, $language));
    }

    public function snake(string $delimiter = '_'): static
    {
        return $this->new(Str::snake($this->value, $delimiter));
    }

    public function start(string $prefix): static
    {
        return $this->new(Str::start($this->value, $prefix));
    }

    public function stripTags(?string $allowedTags = null): static
    {
        return $this->new(strip_tags($this->value, $allowedTags));
    }

    public function studly(): static
    {
        return $this->new(Str::studly($this->value));
    }

    public function substr(int $start, ?int $length = null): static
    {
        return $this->new(Str::substr($this->value, $start, $length));
    }

    public function substrReplace(string|array $replace, int|array $offset = 0, int|array|null $length = null): static
    {
        return $this->new((string)Str::substrReplace($this->value, $replace, $offset, $length));
    }

    public function title(): static
    {
        return $this->new(Str::title($this->value));
    }

    public function trim(?string $characters = null): static
    {
        return $this->new(trim(...array_merge([$this->value], func_get_args())));
    }

    public function ucfirst(): static
    {
        return $this->new(Str::ucfirst($this->value));
    }

    public function upper(): static
    {
        return $this->new(Str::upper($this->value));
    }

    public function whenContains(string|array $needles, callable $callback, ?callable $default = null): static
    {
        return $this->when($this->contains($needles), $callback, $default);
    }

    public function whenContainsAll(array $needles, callable $callback, ?callable $default = null): static
    {
        return $this->when($this->containsAll($needles), $callback, $default);
    }

    public function whenEmpty(callable $callback, ?callable $default = null): static
    {
        return $this->when($this->isEmpty(), $callback, $default);
    }

    public function whenEndsWith(string|array $needles, callable $callback, ?callable $default = null): static
    {
        return $this->when($this->endsWith($needles), $callback, $default);
    }

    public function whenExactly(string $value, callable $callback, callable $default = null): static
    {
        return $this->when($this->exactly($value), $callback, $default);
    }

    public function whenIs(string|array $pattern, callable $callback, ?callable $default = null): static
    {
        return $this->when($this->is($pattern), $callback, $default);
    }

    public function whenIsAscii(callable $callback, ?callable $default = null): static
    {
        return $this->when($this->isAscii(), $callback, $default);
    }

    public function whenIsUuid(callable $callback, ?callable $default = null): static
    {
        return $this->when($this->isUuid(), $callback, $default);
    }

    public function whenNotEmpty(callable $callback, ?callable $default = null): static
    {
        return $this->when($this->isNotEmpty(), $callback, $default);
    }

    public function whenStartsWith(string|array $needles, callable $callback, ?callable $default = null): static
    {
        return $this->when($this->startsWith($needles), $callback, $default);
    }

    public function whenTest(string $pattern, callable $callback, ?callable $default = null): static
    {
        return $this->when($this->test($pattern), $callback, $default);
    }

    public function words(int $words = 100, string $end = '...'): static
    {
        return $this->new(Str::words($this->value, $words, $end));
    }
}
