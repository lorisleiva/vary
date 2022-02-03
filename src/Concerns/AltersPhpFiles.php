<?php

namespace Lorisleiva\Vary\Concerns;

use Closure;
use Lorisleiva\Vary\Variant;

trait AltersPhpFiles
{
    public function addPhpImports(string ...$imports): static
    {
        $imports = array_map(fn (string $import) => "use {$import};", $imports);
        $imports = join(PHP_EOL, $imports);

        return $this->phpImports()->select(fn (Variant $variant) => $variant->appendLine($imports), limit: 1);
    }

    public function deletePhpImports(string ...$imports): static
    {
        if (empty($imports)) {
            return $this->phpImports()->empty();
        }

        $imports = array_map(fn (string $import) => "use {$import};", $imports);

        return $this->phpImports()->selectWithEol(fn (Variant $variant) => $variant->deleteLines($imports));
    }

    public function replacePhpImport(string $search, string $replace): static
    {
        return $this->phpImports()->select(fn (Variant $variant) => $variant->replace($search, $replace));
    }

    public function replacePhpImports(array $replacements): static
    {
        return $this->phpImports()->select(fn (Variant $variant) => $variant->replaceAll($replacements));
    }

    public function selectPhpImports(Closure $callback, ?Closure $replace = null, int $limit = -1, bool $includeEol = false): static
    {
        return $this->phpImports()->select($callback, $replace, $limit, $includeEol);
    }

    public function selectPhpImportsWithEol(Closure $callback, ?Closure $replace = null, int $limit = -1): static
    {
        return $this->selectPhpImports($callback, $replace, $limit, true);
    }

    public function sortPhpImports(?Closure $callback = null): static
    {
        return $this->selectPhpImports(function (Variant $variant) use ($callback) {
            return $variant->sortLines($callback);
        });
    }

    public function sortPhpImportsByLength(): static
    {
        return $this->sortPhpImports(fn (string $value) => strlen($value));
    }
}
