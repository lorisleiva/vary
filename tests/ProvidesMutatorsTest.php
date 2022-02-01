<?php

use Lorisleiva\Vary\Vary;

it('empties the entire content', function () {
    Vary::string('Some text')
        ->empty()
        ->tap(expectVariantToBe(''));
});

it('overrides the entire content', function () {
    Vary::string('Some text')
        ->override('Hello World')
        ->tap(expectVariantToBe('Hello World'));
});

it('prepends some text', function () {
    Vary::string('World')
        ->prepend('Hello ')
        ->tap(expectVariantToBe('Hello World'));
});

it('appends some text', function () {
    Vary::string('Hello')
        ->append(' World')
        ->tap(expectVariantToBe('Hello World'));
});

it('adds some text before some other text', function () {
    $variant = Vary::string('One apple pie. One humble pie.');

    $variant->addBefore('pie', 'hip')->tap(expectVariantToBe('One apple hippie. One humble hippie.'));
    $variant->addBeforeFirst('pie', 'hip')->tap(expectVariantToBe('One apple hippie. One humble pie.'));
    $variant->addBeforeLast('pie', 'hip')->tap(expectVariantToBe('One apple pie. One humble hippie.'));
});

it('prepends the text when adding text before the entire content', function () {
    $variant = Vary::string('Hello World');

    $variant->addBefore('Hello World', 'Say: ')->tap(expectVariantToBe('Say: Hello World'));
    $variant->addBeforeFirst('Hello World', 'Say: ')->tap(expectVariantToBe('Say: Hello World'));
    $variant->addBeforeLast('Hello World', 'Say: ')->tap(expectVariantToBe('Say: Hello World'));
});

it('does not add text before some text that is not found', function () {
    $variant = Vary::string('Hello World');

    $variant->addBefore('NOT_FOUND', 'Test')->tap(expectVariantToBe('Hello World'));
    $variant->addBeforeFirst('NOT_FOUND', 'Test')->tap(expectVariantToBe('Hello World'));
    $variant->addBeforeLast('NOT_FOUND', 'Test')->tap(expectVariantToBe('Hello World'));
    Vary::string('')->addBefore('NOT_FOUND', 'Test')->tap(expectVariantToBe(''));
    Vary::string('')->addBeforeFirst('NOT_FOUND', 'Test')->tap(expectVariantToBe(''));
    Vary::string('')->addBeforeLast('NOT_FOUND', 'Test')->tap(expectVariantToBe(''));
});

it('adds some text before a given pattern', function () {
    $variant = Vary::string('One apple pie. One humble pie.');

    $variant->addBeforePattern('/pie/', 'hip')
        ->tap(expectVariantToBe('One apple hippie. One humble hippie.'));

    $variant->addBeforePattern('/One .*? pie./', 'Say: ')
        ->tap(expectVariantToBe('Say: One apple pie. Say: One humble pie.'));
});

it('adds some text before the first group of a given pattern', function () {
    $variant = Vary::string('One apple pie. One humble pie.');

    $variant->addBeforePatternFirstGroup('/One (.*)/', 'or two ')
        ->tap(expectVariantToBe('One or two apple pie. One humble pie.'));

    $variant->addBeforePatternFirstGroup('/One (.*?) pie./', 'big ')
        ->tap(expectVariantToBe('One big apple pie. One big humble pie.'));
});

it('adds some text after some other text', function () {
    $variant = Vary::string('One apple pie. One humble pie.');

    $variant->addAfter('pie', 'rcing')->tap(expectVariantToBe('One apple piercing. One humble piercing.'));
    $variant->addAfterFirst('pie', 'rcing')->tap(expectVariantToBe('One apple piercing. One humble pie.'));
    $variant->addAfterLast('pie', 'rcing')->tap(expectVariantToBe('One apple pie. One humble piercing.'));
});

it('appends the text when adding text after the entire content', function () {
    $variant = Vary::string('Hello World');

    $variant->addAfter('Hello World', '!')->tap(expectVariantToBe('Hello World!'));
    $variant->addAfterFirst('Hello World', '!')->tap(expectVariantToBe('Hello World!'));
    $variant->addAfterLast('Hello World', '!')->tap(expectVariantToBe('Hello World!'));
});

it('does not add text after some text that is not found', function () {
    $variant = Vary::string('Hello World');

    $variant->addAfter('NOT_FOUND', 'Test')->tap(expectVariantToBe('Hello World'));
    $variant->addAfterFirst('NOT_FOUND', 'Test')->tap(expectVariantToBe('Hello World'));
    $variant->addAfterLast('NOT_FOUND', 'Test')->tap(expectVariantToBe('Hello World'));
    Vary::string('')->addAfter('NOT_FOUND', 'Test')->tap(expectVariantToBe(''));
    Vary::string('')->addAfterFirst('NOT_FOUND', 'Test')->tap(expectVariantToBe(''));
    Vary::string('')->addAfterLast('NOT_FOUND', 'Test')->tap(expectVariantToBe(''));
});

it('adds some text after a given pattern', function () {
    $variant = Vary::string('One apple pie. One humble pie.');

    $variant->addAfterPattern('/pie/', 'rcing')
        ->tap(expectVariantToBe('One apple piercing. One humble piercing.'));

    $variant->addAfterPattern('/One .*? pie./', ' I said!')
        ->tap(expectVariantToBe('One apple pie. I said! One humble pie. I said!'));
});

it('adds some text after the first group of a given pattern', function () {
    $variant = Vary::string('One apple pie. One humble pie.');

    $variant->addAfterPatternFirstGroup('/(.*) humble/', ' super')
        ->tap(expectVariantToBe('One apple pie. One super humble pie.'));

    $variant->addAfterPatternFirstGroup('/One (.*?) pie./', '-ish')
        ->tap(expectVariantToBe('One apple-ish pie. One humble-ish pie.'));
});

it('replaces all instances of one text with another', function () {
    Vary::string('One apple pie. One humble pie. One apple TV.')
        ->replace('One', 'Two')
        ->tap(expectVariantToBe('Two apple pie. Two humble pie. Two apple TV.'));
});

it('replaces multiple instances at onces', function () {
    Vary::string('One apple pie. One humble pie. One apple TV.')
        ->replace(['One', 'pie'], ['Two', 'tarts'])
        ->tap(expectVariantToBe('Two apple tarts. Two humble tarts. Two apple TV.'));
});

it('replaces multiple instances at onces by providing a key/value array', function () {
    Vary::string('One apple pie. One humble pie. One apple TV.')
        ->replaceAll(['One' => 'Two', 'pie' => 'tarts'])
        ->tap(expectVariantToBe('Two apple tarts. Two humble tarts. Two apple TV.'));
});

it('replaces only the first instance of one text with another', function () {
    Vary::string('One apple pie. One humble pie. One apple TV.')
        ->replaceFirst('One', 'Two')
        ->tap(expectVariantToBe('Two apple pie. One humble pie. One apple TV.'));
});

it('replaces only the last instance of one text with another', function () {
    Vary::string('One apple pie. One humble pie. One apple TV.')
        ->replaceLast('One', 'Two')
        ->tap(expectVariantToBe('One apple pie. One humble pie. Two apple TV.'));
});

it('replaces multiple instances of one text with a sequence of other texts', function () {
    Vary::string('One apple pie. One humble pie. One apple TV.')
        ->replaceSequentially('One', ['Two', 'Three'])
        ->tap(expectVariantToBe('Two apple pie. Three humble pie. One apple TV.'));
});

it('replaces text using regular expressions', function () {
    Vary::string('One apple pie. One humble pie. One apple TV.')
        ->replacePattern('/(pie|TV)/', 'super $1')
        ->tap(expectVariantToBe('One apple super pie. One humble super pie. One apple super TV.'));
});

it('replaces text using regular expressions and a callback', function () {
    Vary::string('One apple pie. One humble pie. One apple TV.')
        ->replacePattern('/(pie|TV)/', fn (array $matches) => "super $matches[1]")
        ->tap(expectVariantToBe('One apple super pie. One humble super pie. One apple super TV.'));
});

it('deletes all instances of one or many given texts', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->delete('One ')->tap(expectVariantToBe('apple pie. humble pie. apple TV.'));
    $variant->delete(['One ', ' pie'])->tap(expectVariantToBe('apple. humble. apple TV.'));
});

it('deletes the first or last instance of a given text', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->deleteFirst(' pie')->tap(expectVariantToBe('One apple. One humble pie. One apple TV.'));
    $variant->deleteLast(' pie')->tap(expectVariantToBe('One apple pie. One humble. One apple TV.'));
});

it('deletes text using regular expressions', function () {
    Vary::string('One apple pie. One humble pie. One apple TV.')
        ->deletePattern('/ (pie|TV)/')
        ->tap(expectVariantToBe('One apple. One humble. One apple.'));
});

it('deletes text using the first group of a regular expression', function () {
    Vary::string('One apple pie. One humble pie. One apple TV.')
        ->deletePatternFirstGroup('/One(.*?) pie./')
        ->tap(expectVariantToBe('One pie. One pie. One apple TV.'));
});

it('returns the text matching a given pattern', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->match('/One .*? pie/')->tap(expectVariantToBe('One apple pie'));
    $variant->match('/One (.*?) pie/')->tap(expectVariantToBe('apple'));
});
