<?php

use Lorisleiva\Vary\Vary;

test('appendBeforeWhitespace', function () {
    Vary::string("Hello World \t\n ")
        ->appendBeforeWhitespace(';')
        ->tap(expectVariantToBe("Hello World; \t\n "));
});

test('getLeftWhitespace', function () {
    expect(Vary::string(" \t\n \r Hello World \t\n ")->getLeftWhitespace())->toBe(" \t\n \r ");
    expect(Vary::string("Hello World")->getLeftWhitespace())->toBe('');
});

test('getRightWhitespace', function () {
    expect(Vary::string(" \t\n \r Hello World \t\n ")->getRightWhitespace())->toBe(" \t\n ");
    expect(Vary::string("Hello World")->getRightWhitespace())->toBe('');
});

test('prependAfterWhitespace', function () {
    Vary::string(" \t\n Hello World")
        ->prependAfterWhitespace('// ')
        ->tap(expectVariantToBe(" \t\n // Hello World"));
});

test('selectAfterWhitespace', function () {
    Vary::string("  \t\n  \r\n Hello Moto \n  \t")
        ->selectAfterWhitespace(expectVariantToBe("Hello Moto \n  \t"));
});

test('selectBeforeWhitespace', function () {
    Vary::string("  \t\n  \r\n Hello Moto \n  \t")
        ->selectBeforeWhitespace(expectVariantToBe("  \t\n  \r\n Hello Moto"));
});

test('selectBetweenWhitespace', function () {
    Vary::string("  \t\n  \r\n Hello Moto \n  \t")
        ->selectBetweenWhitespace(expectVariantToBe("Hello Moto"));
});
