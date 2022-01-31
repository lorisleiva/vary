<?php

use Lorisleiva\Vary\Blocks\Block;
use Lorisleiva\Vary\Vary;

it('dummy', function () {
    $variant = Vary::string("A\nA\nB\nA\nA\nA\nC");
    $block = new Block($variant, 'A');

    expect($block->first())->toBe("A\nA");
    expect($block->all())->toBe(["A\nA", "A\nA\nA"]);

    expect($block->first(true))->toBe("A\nA\n");
    expect($block->all(true))->toBe(["A\nA\n", "\nA\nA\nA\n"]);
});
