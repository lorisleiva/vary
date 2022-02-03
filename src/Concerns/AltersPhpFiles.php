<?php

namespace Lorisleiva\Vary\Concerns;

use Lorisleiva\Vary\Blocks\PhpBlock;
use Lorisleiva\Vary\Blocks\PhpImportsBlock;

trait AltersPhpFiles
{
    public function phpBlock(string $pattern): PhpBlock
    {
        return new PhpBlock($this, $pattern);
    }

    public function phpImports(): PhpImportsBlock
    {
        return new PhpImportsBlock($this);
    }
}
