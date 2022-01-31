<?php

namespace Lorisleiva\Vary\Blocks;

use JetBrains\PhpStorm\Pure;
use Lorisleiva\Vary\Variant;

class PhpBlock extends Block
{
    #[Pure] public function __construct(Variant $variant, string $pattern)
    {
        $lineComment = '\/\/.*$';
        $blockComment = '\/\*(?:[^*]|(?:\*[^\/]))*\*\/';
        $newLine = '\s';

        parent::__construct($variant, $pattern, "{$newLine}|{$lineComment}|{$blockComment}");
    }
}
