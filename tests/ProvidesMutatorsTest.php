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

    $variant->addBefore('pie', 'hip')->tap(expectVariantToBe('One apple hippie. One humble pie.'));
    $variant->addBeforeLast('pie', 'hip')->tap(expectVariantToBe('One apple pie. One humble hippie.'));
    // $variant->addBeforeAll('pie', 'hip')->tap(expectVariantToBe('One apple hippie. One humble hippie.'));
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
