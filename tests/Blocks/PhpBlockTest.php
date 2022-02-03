<?php

use Lorisleiva\Vary\Vary;

it('allows PHP line comments', function () {
    $variant = Vary::string(
        <<<PHP
        // Some comment before.
        A // Some comment next to an item.
        // Some comment inside.
        A // Some comment next to the last item.
        // Some comment after.
        PHP
    );

    $variant->phpBlock('A')->match()->tap(expectVariantToBe(
        <<<PHP
        A // Some comment next to an item.
        // Some comment inside.
        A
        PHP
    ));
});

it('allows PHP block comments', function () {
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

    $variant->phpBlock('A')->match()->tap(expectVariantToBe(
        <<<PHP
        A /* Some
        Multiline
        Comment */ A
        /* Some comment inside. */
        A
        PHP
    ));
});

it('allows PHP all types of comments', function () {
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

    expect($variant->phpBlock('A')->matchAll())->toBe([
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

    expect($variant->phpBlock('A')->matchAllWithEol())->toBe([
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
