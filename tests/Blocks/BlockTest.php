<?php

use Lorisleiva\Vary\Vary;

test('empty', function () {
    $variant = Vary::string("A\nA\nB\nA\nA\nA\nC");

    $variant->block('A')->empty()
        ->tap(expectVariantToBe("B\nC"));

    $variant->block('A|B')->empty()
        ->tap(expectVariantToBe("C"));
});

test('match', function () {
    $variant = Vary::string("A\nA\nB\nA\nA\nA\nC");
    $variant->block('A')->match()->tap(expectVariantToBe("A\nA"));
    $variant->block('A|B')->match()->tap(expectVariantToBe("A\nA\nB\nA\nA\nA"));
    $variant->block('B')->match()->tap(expectVariantToBe("B"));
    $variant->block('C')->match()->tap(expectVariantToBe("C"));

    // With custom allowed patterns.
    $variant->block('A', '(?:\s|B)*')->match()->tap(expectVariantToBe("A\nA\nB\nA\nA\nA"));
    $variant->block('A', '')->match()->tap(expectVariantToBe("A"));

    // With more complex pattern.
    Vary::string(
        <<<EOL
        Hello World,
        Hello Loris!
        Hiya, my name is Barney.
        Did someone say hello?
        EOL
    )
        ->block('^.*[Hh]ello.*$')
        ->match()
        ->tap(expectVariantToBe("Hello World,\nHello Loris!"));
});

test('matchAll', function () {
    $variant = Vary::string("A\nA\nB\nA\nA\nA\nC");
    expect($variant->block('A')->matchAll())->toBe(["A\nA", "A\nA\nA"]);
    expect($variant->block('A|B')->matchAll())->toBe(["A\nA\nB\nA\nA\nA"]);
    expect($variant->block('B')->matchAll())->toBe(["B"]);
    expect($variant->block('C')->matchAll())->toBe(["C"]);

    // With custom allowed patterns.
    expect($variant->block('A', '(?:\s|B)*')->matchAll())->toBe(["A\nA\nB\nA\nA\nA"]);
    expect($variant->block('A', '')->matchAll())->toBe(["A", "A", "A", "A", "A"]);

    // With more complex pattern.
    $variant = Vary::string(
        <<<EOL
        Hello World,
        Hello Loris!
        Hiya, my name is Barney.
        Did someone say hello?
        EOL
    );
    expect($variant->block('^.*[Hh]ello.*$')->matchAll())
        ->toBe(["Hello World,\nHello Loris!", "Did someone say hello?"]);
});

test('matchAllWithEol', function () {
    $variant = Vary::string("A\nA\nB\nA\nA\nA\nC");
    expect($variant->block('A')->matchAllWithEol())->toBe(["A\nA\n", "\nA\nA\nA\n"]);
    expect($variant->block('A|B')->matchAllWithEol())->toBe(["A\nA\nB\nA\nA\nA\n"]);
    expect($variant->block('B')->matchAllWithEol())->toBe(["\nB\n"]);
    expect($variant->block('C')->matchAllWithEol())->toBe(["\nC"]);

    // With custom allowed patterns.
    expect($variant->block('A', '(?:\s|B)*')->matchAllWithEol())->toBe(["A\nA\nB\nA\nA\nA\n"]);
    expect($variant->block('A', '')->matchAllWithEol())->toBe(["A\n", "A\n", "\nA\n", "A\n", "A\n"]);

    // With more complex pattern.
    $variant = Vary::string(
        <<<EOL
        Hello World,
        Hello Loris!
        Hiya, my name is Barney.
        Did someone say hello?
        EOL
    );
    expect($variant->block('^.*[Hh]ello.*$')->matchAllWithEol())
        ->toBe(["Hello World,\nHello Loris!\n", "\nDid someone say hello?"]);
});

test('matchWithEol', function () {
    $variant = Vary::string("A\nA\nB\nA\nA\nA\nC");
    $variant->block('A')->matchWithEol()->tap(expectVariantToBe("A\nA\n"));
    $variant->block('A|B')->matchWithEol()->tap(expectVariantToBe("A\nA\nB\nA\nA\nA\n"));
    $variant->block('B')->matchWithEol()->tap(expectVariantToBe("\nB\n"));
    $variant->block('C')->matchWithEol()->tap(expectVariantToBe("\nC"));

    // With custom allowed patterns.
    $variant->block('A', '(?:\s|B)*')->matchWithEol()->tap(expectVariantToBe("A\nA\nB\nA\nA\nA\n"));
    $variant->block('A', '')->matchWithEol()->tap(expectVariantToBe("A\n"));

    // With more complex pattern.
    Vary::string(
        <<<EOL
        Hello World,
        Hello Loris!
        Hiya, my name is Barney.
        Did someone say hello?
        EOL
    )
        ->block('^.*[Hh]ello.*$')
        ->matchWithEol()
        ->tap(expectVariantToBe("Hello World,\nHello Loris!\n"));
});

test('select', function () {
    $variant = Vary::string("A\nA\nB\nA\nA\nA\nC");

    $variant->block('A')->select(overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe("CHANGED\nB\nCHANGED\nC"));

    $variant->block('A|B')->select(overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe("CHANGED\nC"));
});

test('selectWithEol', function () {
    $variant = Vary::string("A\nA\nB\nA\nA\nA\nC");

    $variant->block('A')->selectWithEol(overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe("CHANGEDBCHANGEDC"));

    $variant->block('A|B')->selectWithEol(overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe("CHANGEDC"));
});
