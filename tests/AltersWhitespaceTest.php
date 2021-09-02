<?php

use Lorisleiva\Vary\Vary;

it('selects a fragment before any whitespace', function () {
    Vary::string("  \t\n  \r\n Hello Moto \n  \t")
        ->selectBeforeWhitespace(expectVariantToBe("  \t\n  \r\n Hello Moto"));
});

it('selects a fragment after any whitespace', function () {
    Vary::string("  \t\n  \r\n Hello Moto \n  \t")
        ->selectAfterWhitespace(expectVariantToBe("Hello Moto \n  \t"));
});

it('selects a fragment between any whitespace', function () {
    Vary::string("  \t\n  \r\n Hello Moto \n  \t")
        ->selectBetweenWhitespace(expectVariantToBe("Hello Moto"));
});

it('prepends some text after any whitespace', function () {
    Vary::string(" \t\n Hello World")
        ->prependAfterWhitespace('// ')
        ->tap(expectVariantToBe(" \t\n // Hello World"));
});

it('appends some text before any whitespace', function () {
    Vary::string("Hello World \t\n ")
        ->appendBeforeWhitespace(';')
        ->tap(expectVariantToBe("Hello World; \t\n "));
});

it('returns any whitespace at the left of the content', function () {
    expect(Vary::string(" \t\n \r Hello World \t\n ")->getLeftWhitespace())->toBe(" \t\n \r ");
    expect(Vary::string("Hello World")->getLeftWhitespace())->toBe('');
});

it('returns any whitespace at the right of the content', function () {
    expect(Vary::string(" \t\n \r Hello World \t\n ")->getRightWhitespace())->toBe(" \t\n ");
    expect(Vary::string("Hello World")->getRightWhitespace())->toBe('');
});
