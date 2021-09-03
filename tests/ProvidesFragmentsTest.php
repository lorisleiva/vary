<?php

use Lorisleiva\Vary\Variant;
use Lorisleiva\Vary\Vary;

it('selects all of its content', function () {
    // Either via the "selectAll" method.
    Vary::string('Hello World')
        ->selectAll(expectVariantToBe('Hello World'));

    // Or via the "tap" alias method.
    Vary::string('Hello World')
        ->tap(expectVariantToBe('Hello World'));
});

it('selects a given fragment of text', function () {
    Vary::string('Hello World')
        ->select('World', expectVariantToBe('World'));

    Vary::string("Hello World \n with line break.")
        ->select("World \n with", expectVariantToBe("World \n with"));
});

it('selects a fragment from a given pattern', function () {
    $variant = Vary::string('You can also commit injustice by doing nothing.');

    $variant->selectPattern('/com+it/', expectVariantToBe('commit'));
    $variant->selectPattern('/You.*also/', expectVariantToBe('You can also'));
    $variant->selectPattern('/NOT_FOUND/', expectVariantNotToBeCalled());
});

it('selects multiple fragments from a given pattern', function () {
    Vary::string('You can also commit injustice by doing nothing.')
        ->selectPattern('/also|(doing.*)/', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('You can CHANGED commit injustice by CHANGED'));
});

it('updates matched fragments using a custom replace callback', function () {
    $replaceCallback = fn (array $matches, Closure $next) => $matches[1] . $next($matches[2]) . $matches[3];
    $uppercaseCallback = fn (Variant $variant) => strtoupper($variant);

    Vary::string('You can also commit injustice by doing nothing.')
        ->selectPattern('/You can al(so)(.*) by(.*)/', $uppercaseCallback, $replaceCallback)
        ->tap(expectVariantToBe('so COMMIT INJUSTICE doing nothing.'));
});

it('selects a fragment using the first group of a given pattern', function () {
    $variant = Vary::string('You can also commit injustice by doing nothing.');

    $variant->selectPatternFirstGroup('/You\s(.*)\sby/', expectVariantToBe('can also commit injustice'));
    $variant->selectPatternFirstGroup('/injustice\s([^t]+)/', expectVariantToBe('by doing no'));
    $variant->selectPatternFirstGroup('/CONTENT_(NOT)_FOUND/', expectVariantNotToBeCalled());
});

it('selects multiple fragments using the first group of a given pattern', function () {
    Vary::string('An apple pie, an humble pie and an apple TV.')
        ->selectPatternFirstGroup('/an\s(.*?)\spie/i', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('An CHANGED pie, an CHANGED pie and an apple TV.'));
});

it('selects a fragment before a given text', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->selectBefore('pie', expectVariantToBe('One apple '));
    $variant->selectBefore('pie', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGEDpie. One humble pie. One apple TV.'));

    $variant->selectBeforeIncluded('pie', expectVariantToBe('One apple pie'));
    $variant->selectBeforeIncluded('pie', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGED. One humble pie. One apple TV.'));

    $variant->selectBeforeLast('pie', expectVariantToBe('One apple pie. One humble '));
    $variant->selectBeforeLast('pie', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGEDpie. One apple TV.'));

    $variant->selectBeforeLastIncluded('pie', expectVariantToBe('One apple pie. One humble pie'));
    $variant->selectBeforeLastIncluded('pie', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGED. One apple TV.'));
});

it('replaces an empty fragment before a given text', function () {
    $variant = Vary::string('-content');

    $variant->selectBefore('-', expectVariantToBe(''));
    $variant->selectBefore('-', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGED-content'));

    $variant->selectBeforeIncluded('-', expectVariantToBe('-'));
    $variant->selectBeforeIncluded('-', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGEDcontent'));

    $variant->selectBeforeLast('-', expectVariantToBe(''));
    $variant->selectBeforeLast('-', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGED-content'));

    $variant->selectBeforeLastIncluded('-', expectVariantToBe('-'));
    $variant->selectBeforeLastIncluded('-', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGEDcontent'));
});

it('selects all when the before text could not be found', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->selectBefore('NOT_FOUND', expectVariantToBe('One apple pie. One humble pie. One apple TV.'));
    $variant->selectBefore('NOT_FOUND', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGED'));

    $variant->selectBeforeIncluded('NOT_FOUND', expectVariantToBe('One apple pie. One humble pie. One apple TV.'));
    $variant->selectBeforeIncluded('NOT_FOUND', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGED'));

    Vary::string('')->selectBefore('NOT_FOUND', expectVariantToBe(''));
    Vary::string('')->selectBefore('NOT_FOUND', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGED'));
});

it('updates a fragment before a given text without affecting the rest', function () {
    Vary::string('Some repeated fragment. Some repeated fragment.')
        ->selectBeforeIncluded('fragment', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGED. Some repeated fragment.'));
});

it('selects a fragment after a given text', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->selectAfter('One', expectVariantToBe(' apple pie. One humble pie. One apple TV.'));
    $variant->selectAfter('One', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('OneCHANGED'));

    $variant->selectAfterIncluded('One', expectVariantToBe('One apple pie. One humble pie. One apple TV.'));
    $variant->selectAfterIncluded('One', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGED'));

    $variant->selectAfterLast('One', expectVariantToBe(' apple TV.'));
    $variant->selectAfterLast('One', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('One apple pie. One humble pie. OneCHANGED'));

    $variant->selectAfterLastIncluded('One', expectVariantToBe('One apple TV.'));
    $variant->selectAfterLastIncluded('One', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('One apple pie. One humble pie. CHANGED'));
});

it('replaces an empty fragment after a given text', function () {
    $variant = Vary::string('content-');

    $variant->selectAfter('-', expectVariantToBe(''));
    $variant->selectAfter('-', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('content-CHANGED'));

    $variant->selectAfterIncluded('-', expectVariantToBe('-'));
    $variant->selectAfterIncluded('-', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('contentCHANGED'));

    $variant->selectAfterLast('-', expectVariantToBe(''));
    $variant->selectAfterLast('-', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('content-CHANGED'));

    $variant->selectAfterLastIncluded('-', expectVariantToBe('-'));
    $variant->selectAfterLastIncluded('-', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('contentCHANGED'));
});

it('selects all when the after text could not be found', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->selectAfter('NOT_FOUND', expectVariantToBe('One apple pie. One humble pie. One apple TV.'));
    $variant->selectAfter('NOT_FOUND', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGED'));

    $variant->selectAfterIncluded('NOT_FOUND', expectVariantToBe('One apple pie. One humble pie. One apple TV.'));
    $variant->selectAfterIncluded('NOT_FOUND', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGED'));

    Vary::string('')->selectAfter('NOT_FOUND', expectVariantToBe(''));
    Vary::string('')->selectAfter('NOT_FOUND', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGED'));
});

it('updates a fragment after a given text without affecting the rest', function () {
    Vary::string('Some repeated fragment. Some repeated fragment.')
        ->selectAfterLastIncluded('Some', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('Some repeated fragment. CHANGED'));
});

it('selects a fragment between two given texts', function () {
    $variant = Vary::string('One apple pie. One humble pie. Two berry pies.');

    // Between first and first.
    $variant->selectBetween('One', 'pie', expectVariantToBe(' apple '));
    $variant->selectBetweenFirstAndFirst('One', 'pie', expectVariantToBe(' apple '));
    $variant->selectBetweenFirstAndFirstIncluded('One', 'pie', expectVariantToBe('One apple pie'));

    // Between first and last.
    $variant->selectBetweenFirstAndLast('One', 'pie', expectVariantToBe(' apple pie. One humble pie. Two berry '));
    $variant->selectBetweenFirstAndLastIncluded('One', 'pie', expectVariantToBe('One apple pie. One humble pie. Two berry pie'));

    // Between last and first.
    $variant->selectBetweenLastAndFirst('One', 'pie', expectVariantToBe(' humble '));
    $variant->selectBetweenLastAndFirstIncluded('One', 'pie', expectVariantToBe('One humble pie'));

    // Between last and first.
    $variant->selectBetweenLastAndLast('One', 'pie', expectVariantToBe(' humble pie. Two berry '));
    $variant->selectBetweenLastAndLastIncluded('One', 'pie', expectVariantToBe('One humble pie. Two berry pie'));
});

it('selects as much as it can when the any of the between texts could not be found', function () {
    $variant = Vary::string('One apple pie. One humble pie. Two berry pies.');

    $variant->selectBetweenLastAndFirstIncluded('One', 'NO_END', expectVariantToBe('One humble pie. Two berry pies.'));
    $variant->selectBetweenLastAndFirstIncluded('NO_START', 'pie', expectVariantToBe('One apple pie'));
    $variant->selectBetween('NO_START', 'NO_END', expectVariantToBe('One apple pie. One humble pie. Two berry pies.'));
    Vary::string('')->selectBetween('NO_START', 'NO_END', expectVariantToBe(''));
});
