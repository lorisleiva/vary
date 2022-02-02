<?php

namespace Lorisleiva\Vary;

class Regex
{
    public static function getLinePattern(string $subPattern, bool $includeEol = false, string $delimiter = '#', string $options = 'm'): string
    {
        $subPattern = sprintf('^%s$', $subPattern);

        if ($includeEol) {
            $subPattern = sprintf('%1$s\n?', $subPattern);
        }

        return $delimiter.$subPattern.$delimiter.$options;
    }

    public static function getWildcardLinePattern(string $search, bool $includeEol, bool $ignoreWhitespace, bool $allowWildcards): string
    {
        $spaces = '[^\S\r\n]*';
        $subPattern = static::getWildcardSubPattern($search, $allowWildcards, '#');
        $subPattern = $ignoreWhitespace ? ($spaces.$subPattern.$spaces) : $subPattern;

        return static::getLinePattern($subPattern, $includeEol, '#');
    }

    public static function getWildcardPattern(string $search, bool $allowWildcards): string
    {
        $pattern = static::getWildcardSubPattern($search, $allowWildcards, '#');

        return "#$pattern#";
    }

    public static function getWildcardSubPattern(string $search, bool $allowWildcards, string $delimiter = '#'): string
    {
        $pattern = preg_quote($search, $delimiter);

        if ($allowWildcards) {
            $pattern = str_replace('\*', '.*', $pattern);
        }

        return $pattern;
    }
}
