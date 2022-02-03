<?php

namespace Lorisleiva\Vary\Concerns;

use Closure;
use Lorisleiva\Vary\Regex;

trait AltersBlocks
{
    public function matchAllBlocks(string $pattern, string $allowedPattern = '\s*', bool $includeEol = false): array
    {
        $pattern = Regex::getBlockPattern($pattern, $allowedPattern, $includeEol);

        return $this->matchAll($pattern);
    }

    public function matchAllBlockWithEol(string $pattern, string $allowedPattern = '\s*'): array
    {
        return $this->matchAllBlocks($pattern, $allowedPattern, true);
    }

    public function matchBlock(string $pattern, string $allowedPattern = '\s*', bool $includeEol = false): static
    {
        $pattern = Regex::getBlockPattern($pattern, $allowedPattern, $includeEol);

        return $this->match($pattern);
    }

    public function matchBlockWithEol(string $pattern, string $allowedPattern = '\s*'): static
    {
        return $this->matchBlock($pattern, $allowedPattern, true);
    }

    public function selectBlocks(string $pattern, Closure $closure, int $limit = -1, string $allowedPattern = '\s*', bool $includeEol = false): static
    {
        $pattern = Regex::getBlockPattern($pattern, $allowedPattern, $includeEol);

        return $this->selectMatches($pattern, $closure, null, $limit);
    }

    public function selectPhpBlocks(string $pattern, Closure $closure, int $limit = -1, bool $includeEol = false): static
    {
        $lineComment = '\/\/.*$';
        $blockComment = '\/\*(?:[^*]|(?:\*[^\/]))*\*\/'; // TODO: Try negative look-ahead here.
        $newLine = '\s';
        $allowedPattern = "(?:{$newLine}|{$lineComment}|{$blockComment})*";
        $pattern = Regex::getBlockPattern($pattern, $allowedPattern, $includeEol);

        return $this->selectMatches($pattern, $closure, null, $limit);
    }
}
