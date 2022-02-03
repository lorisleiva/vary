<?php

namespace Lorisleiva\Vary;

class Regex
{
    public static function getBlockPattern(string $pattern, string $allowedPattern, bool $includeEol = false): string
    {
        return sprintf(
            '/%3$s(?:(?:%1$s)(?:%2$s))*(?:(?:%1$s)%3$s)/m',
            $pattern,
            $allowedPattern,
            $includeEol ? '\n?' : ''
        );
    }

    public static function getLinePattern(string $subPattern, bool $includeEol = false, string $delimiter = '#', string $options = 'm'): string
    {
        $subPattern = sprintf('^%s$', $subPattern);

        if ($includeEol) {
            $subPattern = sprintf('(?:%1$s\n|\n%1$s(?!\n)|%1$s)', $subPattern);
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
