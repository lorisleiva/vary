<?php

namespace Lorisleiva\Vary\Blocks;

use Lorisleiva\Vary\Variant;

class Block
{
    protected Variant $variant;
    protected string $pattern;
    protected string $allowedPattern;

    public function __construct(Variant $variant, string $pattern, string $allowedPattern = '\n')
    {
        $this->variant = $variant;
        $this->pattern = $pattern;
        $this->allowedPattern = $allowedPattern;
    }

    public function getPattern(bool $includeEol = false): string
    {
        // ^use [^;]+;$

        return sprintf(
            '/%3$s(?:%1$s%2$s)*(?:%1$s%3$s)/m',
            $this->pattern,
            $this->allowedPattern,
            $includeEol ? '\n?' : ''
        );
    }

    public function first(bool $includeEol = false): string
    {
        return $this->variant->match($this->getPattern($includeEol));
    }

    public function all(bool $includeEol = false): array
    {
        return $this->variant->matchAll($this->getPattern($includeEol));
    }
}
