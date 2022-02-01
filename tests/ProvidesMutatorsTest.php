<?php

use Lorisleiva\Vary\Vary;

test('addAfter', function () {
    Vary::string('One apple pie. One humble pie.')->addAfter('pie', 'rcing')
        ->tap(expectVariantToBe('One apple piercing. One humble piercing.'));

    Vary::string('Hello World')->addAfter('Hello World', '!')
        ->tap(expectVariantToBe('Hello World!'));

    Vary::string('Hello World')->addAfter('NOT_FOUND', 'Test')
        ->tap(expectVariantToBe('Hello World'));

    Vary::string('')->addAfter('NOT_FOUND', 'Test')
        ->tap(expectVariantToBe(''));
});

test('addAfterFirst', function () {
    Vary::string('One apple pie. One humble pie.')->addAfterFirst('pie', 'rcing')
        ->tap(expectVariantToBe('One apple piercing. One humble pie.'));

    Vary::string('Hello World')->addAfterFirst('Hello World', '!')
        ->tap(expectVariantToBe('Hello World!'));

    Vary::string('Hello World')->addAfterFirst('NOT_FOUND', 'Test')
        ->tap(expectVariantToBe('Hello World'));

    Vary::string('')->addAfterFirst('NOT_FOUND', 'Test')
        ->tap(expectVariantToBe(''));
});

test('addAfterLast', function () {
    Vary::string('One apple pie. One humble pie.')->addAfterLast('pie', 'rcing')
        ->tap(expectVariantToBe('One apple pie. One humble piercing.'));

    Vary::string('Hello World')->addAfterLast('Hello World', '!')
        ->tap(expectVariantToBe('Hello World!'));

    Vary::string('Hello World')->addAfterLast('NOT_FOUND', 'Test')
        ->tap(expectVariantToBe('Hello World'));

    Vary::string('')->addAfterLast('NOT_FOUND', 'Test')
        ->tap(expectVariantToBe(''));
});

test('addAfterPattern', function () {
    $variant = Vary::string('One apple pie. One humble pie.');

    $variant->addAfterPattern('/pie/', 'rcing')
        ->tap(expectVariantToBe('One apple piercing. One humble piercing.'));

    $variant->addAfterPattern('/One .*? pie./', ' I said!')
        ->tap(expectVariantToBe('One apple pie. I said! One humble pie. I said!'));
});

test('addAfterPatternFirstGroup', function () {
    $variant = Vary::string('One apple pie. One humble pie.');

    $variant->addAfterPatternFirstGroup('/(.*) humble/', ' super')
        ->tap(expectVariantToBe('One apple pie. One super humble pie.'));

    $variant->addAfterPatternFirstGroup('/One (.*?) pie./', '-ish')
        ->tap(expectVariantToBe('One apple-ish pie. One humble-ish pie.'));
});

test('addBefore', function () {
    Vary::string('One apple pie. One humble pie.')->addBefore('pie', 'hip')
        ->tap(expectVariantToBe('One apple hippie. One humble hippie.'));

    Vary::string('Hello World')->addBefore('Hello World', 'Say: ')
        ->tap(expectVariantToBe('Say: Hello World'));

    Vary::string('Hello World')->addBefore('NOT_FOUND', 'Test')
        ->tap(expectVariantToBe('Hello World'));

    Vary::string('')->addBefore('NOT_FOUND', 'Test')
        ->tap(expectVariantToBe(''));
});

test('addBeforeFirst', function () {
    Vary::string('One apple pie. One humble pie.')->addBeforeFirst('pie', 'hip')
        ->tap(expectVariantToBe('One apple hippie. One humble pie.'));

    Vary::string('Hello World')->addBeforeFirst('Hello World', 'Say: ')
        ->tap(expectVariantToBe('Say: Hello World'));

    Vary::string('Hello World')->addBeforeFirst('NOT_FOUND', 'Test')
        ->tap(expectVariantToBe('Hello World'));

    Vary::string('')->addBeforeFirst('NOT_FOUND', 'Test')
        ->tap(expectVariantToBe(''));
});

test('addBeforeLast', function () {
    Vary::string('One apple pie. One humble pie.')->addBeforeLast('pie', 'hip')
        ->tap(expectVariantToBe('One apple pie. One humble hippie.'));

    Vary::string('Hello World')->addBeforeLast('Hello World', 'Say: ')
        ->tap(expectVariantToBe('Say: Hello World'));

    Vary::string('Hello World')->addBeforeLast('NOT_FOUND', 'Test')
        ->tap(expectVariantToBe('Hello World'));

    Vary::string('')->addBeforeLast('NOT_FOUND', 'Test')
        ->tap(expectVariantToBe(''));
});

test('addBeforePattern', function () {
    $variant = Vary::string('One apple pie. One humble pie.');

    $variant->addBeforePattern('/pie/', 'hip')
        ->tap(expectVariantToBe('One apple hippie. One humble hippie.'));

    $variant->addBeforePattern('/One .*? pie./', 'Say: ')
        ->tap(expectVariantToBe('Say: One apple pie. Say: One humble pie.'));
});

test('addBeforePatternFirstGroup', function () {
    $variant = Vary::string('One apple pie. One humble pie.');

    $variant->addBeforePatternFirstGroup('/One (.*)/', 'or two ')
        ->tap(expectVariantToBe('One or two apple pie. One humble pie.'));

    $variant->addBeforePatternFirstGroup('/One (.*?) pie./', 'big ')
        ->tap(expectVariantToBe('One big apple pie. One big humble pie.'));
});

test('after', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('afterLast', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('append', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('ascii', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('basename', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('before', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('beforeLast', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('between', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('camel', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('classBasename', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('delete', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('deleteFirst', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('deleteLast', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('deletePattern', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('deletePatternFirstGroup', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('dirname', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('empty', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('emptyFragment', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('finish', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('headline', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('kebab', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('limit', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('lower', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('ltrim', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('markdown', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('mask', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('match', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('override', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('padBoth', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('padLeft', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('padRight', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('pipe', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('plural', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('pluralStudly', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('prepend', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('remove', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('repeat', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('replace', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('replaceAll', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('replaceFirst', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('replaceLast', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('replacePattern', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('replaceSequentially', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('reverse', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('rtrim', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('singular', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('slug', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('snake', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('start', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('stripTags', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('studly', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('substr', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('substrReplace', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('title', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('trim', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('ucfirst', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('upper', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('whenContains', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('whenContainsAll', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('whenEmpty', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('whenEndsWith', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('whenExactly', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('whenIs', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('whenIsAscii', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('whenIsUuid', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('whenNotEmpty', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('whenStartsWith', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('whenTest', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});

test('words', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));
});


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
