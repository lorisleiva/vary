<?php

namespace Lorisleiva\Vary;

class Regex
{
    static public function getLinePattern(string $subPattern, bool $includeEol = false, string $delimiter = '#'): string
    {
        return sprintf(
            '%3$s^%1$s$%2$s%3$sm',
            $subPattern,
            $includeEol ? '\n?' : '',
            $delimiter,
        );
    }

    static public function getWildcardPattern(string $search, bool $allowWildcards): string
    {
        $pattern = static::getWildcardSubPattern($search, $allowWildcards);

        return "#$pattern#";
    }

    static public function getWildcardSubPattern(string $search, bool $allowWildcards, string $delimiter = '#'): string
    {
        $pattern = preg_quote($search, $delimiter);

        if ($allowWildcards) {
            $pattern = str_replace('\*', '.*', $pattern);
        }

        return $pattern;
    }
}
