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
}
