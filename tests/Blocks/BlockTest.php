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

it('selects blocks of patterned items', function () {
    $variant = Vary::string("A\nA\nB\nA\nA\nA\nC");
    $block = new Block($variant, 'A');

    $block->select(overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe("CHANGED\nB\nCHANGED\nC"));

    $block->selectWithEol(overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe("CHANGEDBCHANGEDC"));
});

it('prepends content before blocks', function () {
    $variant = Vary::string("A\nB\nA");
    $block = new Block($variant, 'A');

    $block->prepend('1')->tap(expectVariantToBe("1A\nB\nA"));
    $block->prependBeforeEach('1')->tap(expectVariantToBe("1A\nB\n1A"));
    $block->prependLines('1')->tap(expectVariantToBe("1\nA\nB\nA"));
    $block->prependLinesBeforeEach('1')->tap(expectVariantToBe("1\nA\nB\n1\nA"));
});

it('appends content after blocks', function () {
    $variant = Vary::string("A\nB\nA");
    $block = new Block($variant, 'A');

    $block->append('1')->tap(expectVariantToBe("A1\nB\nA"));
    $block->appendAfterEach('1')->tap(expectVariantToBe("A1\nB\nA1"));
    $block->appendLines('1')->tap(expectVariantToBe("A\n1\nB\nA"));
    $block->appendLinesAfterEach('1')->tap(expectVariantToBe("A\n1\nB\nA\n1"));
});

it('replaces content inside blocks', function () {
    $variant = Vary::string(
        <<<EOL
        Hello World,
        Hello Loris!
        Hiya, my name is Loris.
        Did Loris say hello?
        EOL
    );
    $block = new Block($variant, '^.*[Hh]ello.*$');

    $block->replace('Loris', 'Will')->tap(expectVariantToBe(
        <<<EOL
        Hello World,
        Hello Will!
        Hiya, my name is Loris.
        Did Will say hello?
        EOL
    ));

    $block->replaceAll(['Loris' => 'Will'])->tap(expectVariantToBe(
        <<<EOL
        Hello World,
        Hello Will!
        Hiya, my name is Loris.
        Did Will say hello?
        EOL
    ));
});

it('deletes lines inside blocks', function () {
    $variant = Vary::string(
        <<<EOL
        Hello World,
        Hello Loris!
        Hiya, my name is Loris.
        Did Loris say hello?
        EOL
    );
    $block = new Block($variant, '^.*[Hh]ello.*$');

    $block->deleteLine('Hello Loris!')
        ->tap(expectVariantToBe(
            <<<EOL
            Hello World,
            Hiya, my name is Loris.
            Did Loris say hello?
            EOL
        ));

    $block->deleteLinePattern('Hello.*')
        ->tap(expectVariantToBe(
            <<<EOL
            Hiya, my name is Loris.
            Did Loris say hello?
            EOL
        ));
});

it('empties blocks of patterned items', function () {
    $variant = Vary::string("A\nA\nB\nA\nA\nA\nC");
    $block = new Block($variant, 'A');

    $block->empty()
        ->tap(expectVariantToBe("B\nC"));
});
