<?php

use Lorisleiva\Vary\Blocks\Block;
use Lorisleiva\Vary\Vary;

it('parses block of patterned items', function () {
    $variant = Vary::string("A\nA\nB\nA\nA\nA\nC");
    $block = new Block($variant, 'A');

    expect($block->first())->toBe("A\nA");
    expect($block->all())->toBe(["A\nA", "A\nA\nA"]);
    expect($block->firstWithEol())->toBe("A\nA\n");
    expect($block->allWithEol())->toBe(["A\nA\n", "\nA\nA\nA\n"]);
});

it('parses more complex blocks of patterned items', function () {
    $variant = Vary::string(
        <<<EOL
        Hello World,
        Hello Loris!
        Hiya, my name is Barney.
        Did someone say hello?
        EOL
    );
    $block = new Block($variant, '^.*[Hh]ello.*$');

    expect($block->first())->toBe("Hello World,\nHello Loris!");
    expect($block->all())->toBe(["Hello World,\nHello Loris!", "Did someone say hello?"]);
    expect($block->firstWithEol())->toBe("Hello World,\nHello Loris!\n");
    expect($block->allWithEol())->toBe(["Hello World,\nHello Loris!\n", "\nDid someone say hello?"]);
});

it('allows other patterns inside the block', function () {
    $variant = Vary::string("A\nA\nB\nA\nA\nA\nC");
    $block = new Block($variant, 'A', '\n|B');

    expect($block->first())->toBe("A\nA\nB\nA\nA\nA");
    expect($block->all())->toBe(["A\nA\nB\nA\nA\nA"]);
    expect($block->firstWithEol())->toBe("A\nA\nB\nA\nA\nA\n");
    expect($block->allWithEol())->toBe(["A\nA\nB\nA\nA\nA\n"]);
});
