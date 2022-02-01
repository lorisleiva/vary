<?php

namespace Lorisleiva\Vary\Concerns;

use Illuminate\Support\Str;

trait ProvidesAccessors
{
    public function contains(string|array $needles): bool
    {
        return Str::contains($this->value, $needles);
    }

    public function containsAll(array $needles): bool
    {
        return Str::containsAll($this->value, $needles);
    }

    public function endsWith(string|array $needles): bool
    {
        return Str::endsWith($this->value, $needles);
    }

    public function exactly($value): bool
    {
        return $this->value === $value;
    }

    public function explode(string $delimiter, int $limit = PHP_INT_MAX): array
    {
        return explode($delimiter, $this->value, $limit);
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function is(string|array $pattern): bool
    {
        return Str::is($pattern, $this->value);
    }

    public function isAscii(): bool
    {
        return Str::isAscii($this->value);
    }

    public function isEmpty(): bool
    {
        return $this->value === '';
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    public function isUuid(): bool
    {
        return Str::isUuid($this->value);
    }

    public function length(string $encoding = null): int
    {
        return Str::length($this->value, $encoding);
    }

    public function matchAll(string $pattern): array
    {
        return Str::matchAll($pattern, $this->value)->toArray();
    }

    public function scan(string $format): array
    {
        return sscanf($this->value, $format);
    }

    public function split(string|int $pattern, int $limit = -1, int $flags = 0): array
    {
        return Str::of($this->value)->split($pattern, $limit, $flags)->toArray();
    }

    public function startsWith(string|array $needles): bool
    {
        return Str::startsWith($this->value, $needles);
    }

    public function substrCount(string $needle, ?int $offset = null, ?int $length = null): int
    {
        return Str::substrCount($this->value, $needle, $offset ?? 0, $length);
    }

    public function test(string $pattern): bool
    {
        return $this->match($pattern)->isNotEmpty();
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function ucsplit(): array
    {
        return Str::ucsplit($this->value);
    }

    public function wordCount(): int
    {
        return str_word_count($this->value);
    }
}
