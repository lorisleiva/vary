<?php

namespace Lorisleiva\Vary\Blocks;

use Lorisleiva\Vary\Variant;

class PhpImportsBlock extends Block
{
    public function __construct(Variant $variant)
    {
        parent::__construct($variant, '^use [^;]+;$', '\n');
    }
}
