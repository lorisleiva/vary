<?php

use Lorisleiva\Vary\Variant;
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
