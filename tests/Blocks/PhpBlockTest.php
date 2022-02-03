<?php

use Lorisleiva\Vary\Blocks\PhpBlock;
use Lorisleiva\Vary\Vary;

it('parses blocks of patterned items whilst allowing PHP line comments', function () {
    $variant = Vary::string(
        <<<PHP
        // Some comment before.
        A // Some comment next to an item.
        // Some comment inside.
        A // Some comment next to the last item.
        // Some comment after.
        PHP
    );

    $block = new PhpBlock($variant, 'A');

    expect($block->match())->toBe(
        <<<PHP
        A // Some comment next to an item.
        // Some comment inside.
        A
        PHP
    );
});

it('parses blocks of patterned items whilst allowing PHP block comments', function () {
    $variant = Vary::string(
        <<<PHP
        /* Some comment before. */
        A /* Some
        Multiline
        Comment */ A
        /* Some comment inside. */
        A /* Some comment next to the last item. */
        /* Some comment after. */
        PHP
    );

    $block = new PhpBlock($variant, 'A');

    expect($block->match())->toBe(
        <<<PHP
        A /* Some
        Multiline
        Comment */ A
        /* Some comment inside. */
        A
        PHP
    );
});

it('parses blocks of patterned items whilst allowing PHP comments', function () {
    $variant = Vary::string(
        <<<PHP
        // Some comment before.
        A
        // Some comment inside.
        A
        /* Some block comment inside. */
        A /*
            Some multiline
            comment block inside.
        */ A
        // Some comment after.
        PHP
    );

    $block = new PhpBlock($variant, 'A');

    expect($block->matchAll())->toBe([
        <<<PHP
        A
        // Some comment inside.
        A
        /* Some block comment inside. */
        A /*
            Some multiline
            comment block inside.
        */ A
        PHP
    ]);

    expect($block->matchAllWithEol())->toBe([
        <<<PHP

        A
        // Some comment inside.
        A
        /* Some block comment inside. */
        A /*
            Some multiline
            comment block inside.
        */ A

        PHP
    ]);
});
