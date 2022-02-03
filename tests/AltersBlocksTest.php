<?php

use Lorisleiva\Vary\Vary;

test('matchAllBlocks', function () {
    $variant = Vary::string("A\nA\nB\nA\nA\nA\nC");
    expect($variant->matchAllBlocks('A'))->toBe(["A\nA", "A\nA\nA"]);
    expect($variant->matchAllBlocks('A|B'))->toBe(["A\nA\nB\nA\nA\nA"]);
    expect($variant->matchAllBlocks('B'))->toBe(["B"]);
    expect($variant->matchAllBlocks('C'))->toBe(["C"]);

    // With custom allowed patterns.
    expect($variant->matchAllBlocks('A', '(?:\s|B)*'))->toBe(["A\nA\nB\nA\nA\nA"]);
    expect($variant->matchAllBlocks('A', ''))->toBe(["A", "A", "A", "A", "A"]);

    // With more complex pattern.
    $variant = Vary::string(
        <<<EOL
        Hello World,
        Hello Loris!
        Hiya, my name is Barney.
        Did someone say hello?
        EOL
    );
    expect($variant->matchAllBlocks('^.*[Hh]ello.*$'))
        ->toBe(["Hello World,\nHello Loris!", "Did someone say hello?"]);
});

test('matchAllBlockWithEol', function () {
    $variant = Vary::string("A\nA\nB\nA\nA\nA\nC");
    expect($variant->matchAllBlockWithEol('A'))->toBe(["A\nA\n", "\nA\nA\nA\n"]);
    expect($variant->matchAllBlockWithEol('A|B'))->toBe(["A\nA\nB\nA\nA\nA\n"]);
    expect($variant->matchAllBlockWithEol('B'))->toBe(["\nB\n"]);
    expect($variant->matchAllBlockWithEol('C'))->toBe(["\nC"]);

    // With custom allowed patterns.
    expect($variant->matchAllBlockWithEol('A', '(?:\s|B)*'))->toBe(["A\nA\nB\nA\nA\nA\n"]);
    expect($variant->matchAllBlockWithEol('A', ''))->toBe(["A\n", "A\n", "\nA\n", "A\n", "A\n"]);

    // With more complex pattern.
    $variant = Vary::string(
        <<<EOL
        Hello World,
        Hello Loris!
        Hiya, my name is Barney.
        Did someone say hello?
        EOL
    );
    expect($variant->matchAllBlockWithEol('^.*[Hh]ello.*$'))
        ->toBe(["Hello World,\nHello Loris!\n", "\nDid someone say hello?"]);
});

test('matchBlock', function () {
    $variant = Vary::string("A\nA\nB\nA\nA\nA\nC");
    $variant->matchBlock('A')->tap(expectVariantToBe("A\nA"));
    $variant->matchBlock('A|B')->tap(expectVariantToBe("A\nA\nB\nA\nA\nA"));
    $variant->matchBlock('B')->tap(expectVariantToBe("B"));
    $variant->matchBlock('C')->tap(expectVariantToBe("C"));

    // With custom allowed patterns.
    $variant->matchBlock('A', '(?:\s|B)*')->tap(expectVariantToBe("A\nA\nB\nA\nA\nA"));
    $variant->matchBlock('A', '')->tap(expectVariantToBe("A"));

    // With more complex pattern.
    Vary::string(
        <<<EOL
        Hello World,
        Hello Loris!
        Hiya, my name is Barney.
        Did someone say hello?
        EOL
    )
        ->matchBlock('^.*[Hh]ello.*$')
        ->tap(expectVariantToBe("Hello World,\nHello Loris!"));
});

test('matchBlockWithEol', function () {
    $variant = Vary::string("A\nA\nB\nA\nA\nA\nC");
    $variant->matchBlockWithEol('A')->tap(expectVariantToBe("A\nA\n"));
    $variant->matchBlockWithEol('A|B')->tap(expectVariantToBe("A\nA\nB\nA\nA\nA\n"));
    $variant->matchBlockWithEol('B')->tap(expectVariantToBe("\nB\n"));
    $variant->matchBlockWithEol('C')->tap(expectVariantToBe("\nC"));

    // With custom allowed patterns.
    $variant->matchBlockWithEol('A', '(?:\s|B)*')->tap(expectVariantToBe("A\nA\nB\nA\nA\nA\n"));
    $variant->matchBlockWithEol('A', '')->tap(expectVariantToBe("A\n"));

    // With more complex pattern.
    Vary::string(
        <<<EOL
        Hello World,
        Hello Loris!
        Hiya, my name is Barney.
        Did someone say hello?
        EOL
    )
        ->matchBlockWithEol('^.*[Hh]ello.*$')
        ->tap(expectVariantToBe("Hello World,\nHello Loris!\n"));
});

test('selectBlocks', function () {
    //
});

test('selectPhpBlocks', function () {
    //
});
