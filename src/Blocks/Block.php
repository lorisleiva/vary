<?php

namespace Lorisleiva\Vary\Blocks;

use Closure;
use Lorisleiva\Vary\Regex;
use Lorisleiva\Vary\Variant;

class Block
{
    protected Variant $variant;
    protected string $pattern;
    protected string $allowedPattern;

    public function __construct(Variant $variant, string $pattern, string $allowedPattern = '\s*')
    {
        $this->variant = $variant;
        $this->pattern = $pattern;
        $this->allowedPattern = $allowedPattern;
    }

    public function empty(): Variant
    {
        return $this->selectWithEol(fn (Variant $variant) => $variant->emptyFragment());
    }

    public function match(bool $includeEol = false): Variant
    {
        return $this->variant->match($this->getPattern($includeEol));
    }

    public function matchAll(bool $includeEol = false): array
    {
        return $this->variant->matchAll($this->getPattern($includeEol));
    }

    public function matchAllWithEol(): array
    {
        return $this->matchAll(true);
    }

    public function matchWithEol(): Variant
    {
        return $this->match(true);
    }

    public function select(Closure $callback, ?Closure $replace = null, int $limit = -1, bool $includeEol = false): Variant
    {
        return $this->variant->selectMatches($this->getPattern($includeEol), $callback, $replace, $limit);
    }

    public function selectWithEol(Closure $callback, ?Closure $replace = null, int $limit = -1): Variant
    {
        return $this->select($callback, $replace, $limit, true);
    }

    protected function getPattern(bool $includeEol = false): string
    {
        return Regex::getBlockPattern($this->pattern, $this->allowedPattern, $includeEol);
    }
}
