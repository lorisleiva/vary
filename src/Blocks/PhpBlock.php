<?php

namespace Lorisleiva\Vary\Blocks;

use JetBrains\PhpStorm\Pure;
use Lorisleiva\Vary\Variant;

class PhpBlock extends Block
{
    #[Pure] public function __construct(Variant $variant, string $pattern)
    {
        $lineComment = '\s*\/\/.*$';
        $blockComment = '\s*(?:\/\*(?:[^*]|(?:\*[^\/]))*\*\/)\s*';
        $newLine = '\n';

        parent::__construct($variant, $pattern, "{$newLine}|{$lineComment}|{$blockComment}");
    }
}
