<?php

namespace Lorisleiva\Vary\Blocks;

use Lorisleiva\Vary\Regex;
use Lorisleiva\Vary\Variant;

class PhpBlock extends Block
{
    public function __construct(Variant $variant, string $pattern)
    {
        parent::__construct($variant, $pattern, Regex::getPhpBlockAllowedPattern());
    }
}
