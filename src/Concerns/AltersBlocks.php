<?php

namespace Lorisleiva\Vary\Concerns;

use Lorisleiva\Vary\Blocks\Block;
use Lorisleiva\Vary\Blocks\PhpBlock;
use Lorisleiva\Vary\Blocks\PhpImportsBlock;

trait AltersBlocks
{
    public function block(string $pattern, string $allowedPattern = '\s*'): Block
    {
        return new Block($this, $pattern, $allowedPattern);
    }

    public function phpBlock(string $pattern): PhpBlock
    {
        return new PhpBlock($this, $pattern);
    }

    public function phpImports(): PhpImportsBlock
    {
        return new PhpImportsBlock($this);
    }
}
