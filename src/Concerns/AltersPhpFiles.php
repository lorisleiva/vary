<?php

namespace Lorisleiva\Vary\Concerns;

use Closure;

trait AltersPhpFiles
{
    protected static string $phpImportsPattern = '/(?:^use [^;]+;$\n)*(?:^use [^;]+;$)/m';
    protected static string $phpImportsPatternWithEol = '/(?:^use [^;]+;$\n)*(?:^use [^;]+;$\n?)/m';

    public function selectPhpImports(Closure $callback, ?Closure $replace = null, int $limit = -1, bool $includeEol = false): static
    {
        $pattern = $includeEol ? static::$phpImportsPatternWithEol : static::$phpImportsPattern;

        return $this->selectPattern($pattern, $callback, $replace, $limit);
    }

    public function selectPhpImportsWithEol(Closure $callback, ?Closure $replace = null, int $limit = -1): static
    {
        return $this->selectPhpImports($callback, $replace, $limit, true);
    }
}
