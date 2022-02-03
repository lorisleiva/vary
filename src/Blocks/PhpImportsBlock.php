<?php

namespace Lorisleiva\Vary\Blocks;

use Closure;
use Lorisleiva\Vary\Variant;

class PhpImportsBlock extends Block
{
    public function __construct(Variant $variant)
    {
        parent::__construct($variant, '^use [^;]+;$', '\n');
    }

    public function add(string ...$imports): Variant
    {
        $imports = array_map(fn (string $import) => "use {$import};", $imports);
        $imports = join(PHP_EOL, $imports);

        return $this->select(fn (Variant $variant) => $variant->appendLine($imports), limit: 1);
    }

    public function delete(string ...$imports): Variant
    {
        if (empty($imports)) {
            return $this->empty();
        }

        $imports = array_map(fn (string $import) => "use {$import};", $imports);

        return $this->selectWithEol(fn (Variant $variant) => $variant->deleteLines($imports));
    }

    public function replace(string $search, string $replace): Variant
    {
        return $this->select(fn (Variant $variant) => $variant->replace($search, $replace));
    }

    public function replaceAll(array $replacements): Variant
    {
        return $this->select(fn (Variant $variant) => $variant->replaceAll($replacements));
    }

    public function sort(?Closure $callback = null): Variant
    {
        return $this->select(function (Variant $variant) use ($callback) {
            return $variant->sortLines($callback);
        });
    }

    public function sortByLength(): Variant
    {
        return $this->sort(fn (string $value) => strlen($value));
    }
}
