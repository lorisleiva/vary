<?php

namespace Lorisleiva\Vary\Concerns;

use Lorisleiva\Vary\Blocks\Block;

trait AltersBlocks
{
    public function block(string $pattern, string $allowedPattern = '\s*'): Block
    {
        return new Block($this, $pattern, $allowedPattern);
    }
}
