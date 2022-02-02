<?php

use Lorisleiva\Vary\Variant;
use Lorisleiva\Vary\Vary;

test('select', function () {
    Vary::string('Hello World')
        ->select('World', expectVariantToBe('World'));

    Vary::string("Hello World \n with line break.")
        ->select("World \n with", expectVariantToBe("World \n with"));

    Vary::string('Hello World, Hello Kitty!')
        ->select('Hello', overrideVariantTo('Bye'))
        ->tap(expectVariantToBe('Bye World, Bye Kitty!'));

    Vary::string('Hello World')
        ->select('*World', expectVariantToBe('Hello World'));

    Vary::string('Hello World')
        ->select('o*o', expectVariantToBe('o Wo'));

    Vary::string('Hello World')
        ->select('*X*', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('Hello World'));
});

test('selectAfter', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->selectAfter('One', expectVariantToBe(' apple pie. One humble pie. One apple TV.'));
    $variant->selectAfter('One', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('OneCHANGED'));

    $variant->selectAfter('NOT_FOUND', expectVariantToBe('One apple pie. One humble pie. One apple TV.'));
    $variant->selectAfter('NOT_FOUND', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('One apple pie. One humble pie. One apple TV.'));

    Vary::string('content-')->selectAfter('-', expectVariantToBe(''));
    Vary::string('content-')->selectAfter('-', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('content-CHANGED'));
});

test('selectAfterIncluded', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->selectAfterIncluded('One', expectVariantToBe('One apple pie. One humble pie. One apple TV.'));
    $variant->selectAfterIncluded('One', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGED'));

    $variant->selectAfterIncluded('NOT_FOUND', expectVariantToBe('One apple pie. One humble pie. One apple TV.'));
    $variant->selectAfterIncluded('NOT_FOUND', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('One apple pie. One humble pie. One apple TV.'));
});

test('selectAfterLast', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->selectAfterLast('One', expectVariantToBe(' apple TV.'));
    $variant->selectAfterLast('One', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('One apple pie. One humble pie. OneCHANGED'));

    $variant->selectAfterLast('NOT_FOUND', expectVariantToBe('One apple pie. One humble pie. One apple TV.'));
    $variant->selectAfterLast('NOT_FOUND', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('One apple pie. One humble pie. One apple TV.'));
});

test('selectAfterLastIncluded', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->selectAfterLastIncluded('One', expectVariantToBe('One apple TV.'));
    $variant->selectAfterLastIncluded('One', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('One apple pie. One humble pie. CHANGED'));

    $variant->selectAfterLastIncluded('NOT_FOUND', expectVariantToBe('One apple pie. One humble pie. One apple TV.'));
    $variant->selectAfterLastIncluded('NOT_FOUND', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('One apple pie. One humble pie. One apple TV.'));

    Vary::string('Some repeated fragment. Some repeated fragment.')
        ->selectAfterLastIncluded('Some', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('Some repeated fragment. CHANGED'));
});

test('selectBefore', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->selectBefore('pie', expectVariantToBe('One apple '));
    $variant->selectBefore('pie', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGEDpie. One humble pie. One apple TV.'));

    $variant->selectBefore('NOT_FOUND', expectVariantToBe('One apple pie. One humble pie. One apple TV.'));
    $variant->selectBefore('NOT_FOUND', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('One apple pie. One humble pie. One apple TV.'));

    Vary::string('-content')->selectBefore('-', expectVariantToBe(''));
    Vary::string('-content')->selectBefore('-', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGED-content'));
});

test('selectBeforeIncluded', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->selectBeforeIncluded('pie', expectVariantToBe('One apple pie'));
    $variant->selectBeforeIncluded('pie', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGED. One humble pie. One apple TV.'));

    $variant->selectBeforeIncluded('NOT_FOUND', expectVariantToBe('One apple pie. One humble pie. One apple TV.'));
    $variant->selectBeforeIncluded('NOT_FOUND', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('One apple pie. One humble pie. One apple TV.'));

    Vary::string('Some repeated fragment. Some repeated fragment.')
        ->selectBeforeIncluded('fragment', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGED. Some repeated fragment.'));
});

test('selectBeforeLast', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->selectBeforeLast('pie', expectVariantToBe('One apple pie. One humble '));
    $variant->selectBeforeLast('pie', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGEDpie. One apple TV.'));

    $variant->selectBeforeLast('NOT_FOUND', expectVariantToBe('One apple pie. One humble pie. One apple TV.'));
    $variant->selectBeforeLast('NOT_FOUND', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('One apple pie. One humble pie. One apple TV.'));
});

test('selectBeforeLastIncluded', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->selectBeforeLastIncluded('pie', expectVariantToBe('One apple pie. One humble pie'));
    $variant->selectBeforeLastIncluded('pie', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGED. One apple TV.'));

    $variant->selectBeforeLastIncluded('NOT_FOUND', expectVariantToBe('One apple pie. One humble pie. One apple TV.'));
    $variant->selectBeforeLastIncluded('NOT_FOUND', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('One apple pie. One humble pie. One apple TV.'));
});

test('selectBetween', function () {
    $variant = Vary::string('One apple pie. One humble pie. Two berry pies.');

    // Between first and last.
    $variant->selectBetween('One', 'pie', expectVariantToBe(' apple pie. One humble pie. Two berry '));
    $variant->selectBetween('One', 'pie', expectVariantToBe(' apple pie. One humble pie. Two berry '), fromLast: false, toLast: true);
    $variant->selectBetween('One', 'pie', expectVariantToBe('One apple pie. One humble pie. Two berry '), fromLast: false, fromIncluded: true, toLast: true);
    $variant->selectBetween('One', 'pie', expectVariantToBe('One apple pie. One humble pie. Two berry pie'), fromLast: false, fromIncluded: true, toLast: true, toIncluded: true);
    $variant->selectBetween('One', 'pie', expectVariantToBe(' apple pie. One humble pie. Two berry pie'), fromLast: false, toLast: true, toIncluded: true);

    // Between first and first.
    $variant->selectBetween('One', 'pie', expectVariantToBe(' apple '), fromLast: false, toLast: false);
    $variant->selectBetween('One', 'pie', expectVariantToBe('One apple '), fromLast: false, fromIncluded: true, toLast: false);
    $variant->selectBetween('One', 'pie', expectVariantToBe('One apple pie'), fromLast: false, fromIncluded: true, toLast: false, toIncluded: true);
    $variant->selectBetween('One', 'pie', expectVariantToBe(' apple pie'), fromLast: false, toLast: false, toIncluded: true);

    // Between last and first.
    $variant->selectBetween('One', 'pie', expectVariantToBe(' humble '), fromLast: true, toLast: false);
    $variant->selectBetween('One', 'pie', expectVariantToBe('One humble '), fromLast: true, fromIncluded: true, toLast: false);
    $variant->selectBetween('One', 'pie', expectVariantToBe('One humble pie'), fromLast: true, fromIncluded: true, toLast: false, toIncluded: true);
    $variant->selectBetween('One', 'pie', expectVariantToBe(' humble pie'), fromLast: true, toLast: false, toIncluded: true);

    // Between last and last.
    $variant->selectBetween('One', 'pie', expectVariantToBe(' humble pie. Two berry '), fromLast: true, toLast: true);
    $variant->selectBetween('One', 'pie', expectVariantToBe('One humble pie. Two berry '), fromLast: true, fromIncluded: true, toLast: true);
    $variant->selectBetween('One', 'pie', expectVariantToBe('One humble pie. Two berry pie'), fromLast: true, fromIncluded: true, toLast: true, toIncluded: true);
    $variant->selectBetween('One', 'pie', expectVariantToBe(' humble pie. Two berry pie'), fromLast: true, toLast: true, toIncluded: true);

    // It does not select if either end does not exists.
    $variant->selectBetween('One', 'NO_END', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('One apple pie. One humble pie. Two berry pies.'));
    $variant->selectBetween('NO_START', 'pie', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('One apple pie. One humble pie. Two berry pies.'));
    $variant->selectBetween('NO_START', 'NO_END', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('One apple pie. One humble pie. Two berry pies.'));
    Vary::string('')->selectBetween('NO_START', 'NO_END', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe(''));
});

test('selectBetweenIncluded', function () {
    $variant = Vary::string('One apple pie. One humble pie. Two berry pies.');

    $variant->selectBetweenIncluded('One', 'pie', expectVariantToBe('One apple pie. One humble pie. Two berry pie'));
    $variant->selectBetweenIncluded('One', 'pie', expectVariantToBe('One apple pie. One humble pie. Two berry pie'), fromLast: false, toLast: true);
    $variant->selectBetweenIncluded('One', 'pie', expectVariantToBe('One apple pie'), fromLast: false, toLast: false);
    $variant->selectBetweenIncluded('One', 'pie', expectVariantToBe('One humble pie'), fromLast: true, toLast: false);
    $variant->selectBetweenIncluded('One', 'pie', expectVariantToBe('One humble pie. Two berry pie'), fromLast: true, toLast: true);
});

test('selectExact', function () {
    Vary::string('Hello World')
        ->selectExact('World', expectVariantToBe('World'));

    Vary::string('Hello World')
        ->selectExact('*World', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('Hello World'));
});

test('selectMatches', function () {
    $variant = Vary::string('You can also commit injustice by doing nothing.');

    // Select all match by default.
    $variant->selectMatches('/com+it/', expectVariantToBe('commit'));
    $variant->selectMatches('/You.*also/', expectVariantToBe('You can also'));
    $variant->selectMatches('/NOT_FOUND/', expectVariantNotToBeCalled());

    // Select first group if exists.
    $variant->selectMatches('/You\s(.*)\sby/', expectVariantToBe('can also commit injustice'));
    $variant->selectMatches('/injustice\s([^t]+)/', expectVariantToBe('by doing no'));
    $variant->selectMatches('/CONTENT_(NOT)_FOUND/', expectVariantNotToBeCalled());

    // Why not both?
    $variant->selectMatches('/also|(doing.*)/', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('You can CHANGED commit injustice by CHANGED'));

    Vary::string('An apple pie, an humble pie and an apple TV.')
        ->selectMatches('/an\s(.*?)\spie/i', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('An CHANGED pie, an CHANGED pie and an apple TV.'));

    // Supports more complex replacements.
    $variant->selectMatches(
        pattern: '/You can al(so)(.*) by(.*)/',
        callback: fn (Variant $variant) => $variant->upper(),
        replace: fn (array $matches, Closure $next) => $matches[1] . $next($matches[2]) . $matches[3],
    )->tap(expectVariantToBe('so COMMIT INJUSTICE doing nothing.'));
});
