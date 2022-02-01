<?php

use Lorisleiva\Vary\Variant;
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

test('addAfterMatches', function () {
    $variant = Vary::string('One apple pie. One humble pie.');

    $variant->addAfterMatches('/pie/', 'rcing')
        ->tap(expectVariantToBe('One apple piercing. One humble piercing.'));

    $variant->addAfterMatches('/One .*? pie./', ' I said!')
        ->tap(expectVariantToBe('One apple pie. I said! One humble pie. I said!'));

    $variant->addAfterMatches('/(.*) humble/', ' super')
        ->tap(expectVariantToBe('One apple pie. One super humble pie.'));

    $variant->addAfterMatches('/One (.*?) pie./', '-ish')
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

test('addBeforeMatches', function () {
    $variant = Vary::string('One apple pie. One humble pie.');

    $variant->addBeforeMatches('/pie/', 'hip')
        ->tap(expectVariantToBe('One apple hippie. One humble hippie.'));

    $variant->addBeforeMatches('/One .*? pie./', 'Say: ')
        ->tap(expectVariantToBe('Say: One apple pie. Say: One humble pie.'));

    $variant->addBeforeMatches('/One (.*)/', 'or two ')
        ->tap(expectVariantToBe('One or two apple pie. One humble pie.'));

    $variant->addBeforeMatches('/One (.*?) pie./', 'big ')
        ->tap(expectVariantToBe('One big apple pie. One big humble pie.'));
});

test('after', function () {
    $variant = Vary::string('One apple pie. One humble pie.');

    $variant->after('One')->tap(expectVariantToBe(' apple pie. One humble pie.'));
    $variant->after('pie')->tap(expectVariantToBe('. One humble pie.'));
    $variant->after('NOT_FOUND')->tap(expectVariantToBe('One apple pie. One humble pie.'));
});

test('afterLast', function () {
    $variant = Vary::string('One apple pie. One humble pie.');

    $variant->afterLast('One')->tap(expectVariantToBe(' humble pie.'));
    $variant->afterLast('pie')->tap(expectVariantToBe('.'));
    $variant->afterLast('NOT_FOUND')->tap(expectVariantToBe('One apple pie. One humble pie.'));
});

test('append', function () {
    Vary::string('Some Text')->append(' And More Text')
        ->tap(expectVariantToBe('Some Text And More Text'));

    Vary::string('Some Text')->append(' And', ' More', ' Text')
        ->tap(expectVariantToBe('Some Text And More Text'));

    Vary::string('')->append('Foo')
        ->tap(expectVariantToBe('Foo'));
});

test('ascii', function () {
    Vary::string('Some Text')->ascii()
        ->tap(expectVariantToBe('Some Text'));

    Vary::string('Bonjour mon cœur, ça va?')->ascii()
        ->tap(expectVariantToBe('Bonjour mon coeur, ca va?'));

    Vary::string('Hey 👋')->ascii()
        ->tap(expectVariantToBe('Hey '));
});

test('basename', function () {
    Vary::string('Some Text')->basename()
        ->tap(expectVariantToBe('Some Text'));

    Vary::string('/Some/Path/To/A/File.php')->basename()
        ->tap(expectVariantToBe('File.php'));

    Vary::string('/Some/Path/To/A/File.php')->basename('.php')
        ->tap(expectVariantToBe('File'));

    Vary::string('~/Code/vary')->basename()
        ->tap(expectVariantToBe('vary'));
});

test('before', function () {
    $variant = Vary::string('Say: One apple pie. One humble pie.');

    $variant->before('One')->tap(expectVariantToBe('Say: '));
    $variant->before('Say:')->tap(expectVariantToBe(''));
    $variant->before('pie')->tap(expectVariantToBe('Say: One apple '));
    $variant->before('NOT_FOUND')->tap(expectVariantToBe('Say: One apple pie. One humble pie.'));
});

test('beforeLast', function () {
    $variant = Vary::string('Say: One apple pie. One humble pie.');

    $variant->beforeLast('One')->tap(expectVariantToBe('Say: One apple pie. '));
    $variant->beforeLast('pie')->tap(expectVariantToBe('Say: One apple pie. One humble '));
    $variant->beforeLast('NOT_FOUND')->tap(expectVariantToBe('Say: One apple pie. One humble pie.'));
});

test('between', function () {
    $variant = Vary::string('One apple pie. One humble pie.');

    $variant->between('One', 'pie')->tap(expectVariantToBe(' apple pie. One humble '));
    $variant->between('pie', 'One')->tap(expectVariantToBe('. '));
    $variant->between('One', 'NOT_FOUND')->tap(expectVariantToBe(' apple pie. One humble pie.'));
    $variant->between('NOT_FOUND', 'pie')->tap(expectVariantToBe('One apple pie. One humble '));
    $variant->between('NOT_FOUND', 'NOT_FOUND')->tap(expectVariantToBe('One apple pie. One humble pie.'));
    $variant->between('', '')->tap(expectVariantToBe('One apple pie. One humble pie.'));
});

test('camel', function () {
    Vary::string('some-text')->camel()->tap(expectVariantToBe('someText'));
    Vary::string('some_text')->camel()->tap(expectVariantToBe('someText'));
    Vary::string('Some more text')->camel()->tap(expectVariantToBe('someMoreText'));
});

test('classBasename', function () {
    Vary::string('App\Models\User')->classBasename()
        ->tap(expectVariantToBe('User'));
});

test('delete', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->delete('One ')->tap(expectVariantToBe('apple pie. humble pie. apple TV.'));
    $variant->delete('one ', false)->tap(expectVariantToBe('apple pie. humble pie. apple TV.'));
    $variant->delete(['One ', ' pie'])->tap(expectVariantToBe('apple. humble. apple TV.'));
});

test('deleteFirst', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->deleteFirst(' pie')->tap(expectVariantToBe('One apple. One humble pie. One apple TV.'));
    $variant->deleteFirst('apple ')->tap(expectVariantToBe('One pie. One humble pie. One apple TV.'));
});

test('deleteLast', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->deleteLast(' pie')->tap(expectVariantToBe('One apple pie. One humble. One apple TV.'));
    $variant->deleteLast(' apple')->tap(expectVariantToBe('One apple pie. One humble pie. One TV.'));
});

test('deleteMatches', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->deleteMatches('/ (?:pie|TV)/')
        ->tap(expectVariantToBe('One apple. One humble. One apple.'));

    $variant->deleteMatches('/One(.*?) pie./')
        ->tap(expectVariantToBe('One pie. One pie. One apple TV.'));
});

test('dirname', function () {
    Vary::string('Some Text')->dirname()
        ->tap(expectVariantToBe('.'));

    Vary::string('/Some/Path/To/A/File.php')->dirname()
        ->tap(expectVariantToBe('/Some/Path/To/A'));

    Vary::string('/Some/Path/To/A/File.php')->dirname(2)
        ->tap(expectVariantToBe('/Some/Path/To'));

    Vary::string('/Some/Path/To/A/File.php')->dirname(3)
        ->tap(expectVariantToBe('/Some/Path'));

    Vary::string('~/Code/vary')->dirname()
        ->tap(expectVariantToBe('~/Code'));
});

test('empty', function () {
    Vary::string('Some Text')->empty()
        ->tap(expectVariantToBe(''));

    Vary::string("\nSome Text\n")->empty()
        ->tap(expectVariantToBe(''));
});

test('emptyFragment', function () {
    Vary::string('Some Text')->emptyFragment()
        ->tap(expectVariantToBe(''));

    Vary::string("Some Text\n")->emptyFragment()
        ->tap(expectVariantToBe(''));

    Vary::string("\nSome Text")->emptyFragment()
        ->tap(expectVariantToBe(''));

    // This ensures, if we try to remove a fragment in the middle
    // of some content, the previous and next parts of the
    // content do not lose the new line separating them.
    Vary::string("\nSome Text\n")->emptyFragment()
        ->tap(expectVariantToBe("\n"));
});

test('finish', function () {
    Vary::string('Some Text')->finish(' Text')
        ->tap(expectVariantToBe('Some Text'));

    Vary::string('Some Magic')->finish(' Text')
        ->tap(expectVariantToBe('Some Magic Text'));

    Vary::string('https://example.com/api/v1/articles')->finish('/')
        ->tap(expectVariantToBe('https://example.com/api/v1/articles/'));

    Vary::string('https://example.com/api/v1/articles/')->finish('/')
        ->tap(expectVariantToBe('https://example.com/api/v1/articles/'));
});

test('headline', function () {
    Vary::string('some text')->headline()->tap(expectVariantToBe('Some Text'));
    Vary::string('some-text')->headline()->tap(expectVariantToBe('Some Text'));
    Vary::string('some_text')->headline()->tap(expectVariantToBe('Some Text'));
    Vary::string('someText')->headline()->tap(expectVariantToBe('Some Text'));
});

test('kebab', function () {
    Vary::string('Some Text')->kebab()->tap(expectVariantToBe('some-text'));
    Vary::string('someText')->kebab()->tap(expectVariantToBe('some-text'));
});

test('limit', function () {
    Vary::string('Some Text')->limit(3)
        ->tap(expectVariantToBe('Som...'));

    Vary::string('Some Text')->limit(5)
        ->tap(expectVariantToBe('Some...'));

    Vary::string('Some Text')->limit(4, '[redacted]')
        ->tap(expectVariantToBe('Some[redacted]'));
});

test('lower', function () {
    Vary::string('Some MAGIC TeXt')->lower()
        ->tap(expectVariantToBe('some magic text'));
});

test('ltrim', function () {
    Vary::string("   \n  \t Some Text")->ltrim()
        ->tap(expectVariantToBe('Some Text'));

    Vary::string(' Some Text')->ltrim('S ')
        ->tap(expectVariantToBe('ome Text'));

    Vary::string(' Some Text')->ltrim('SomeT ')
        ->tap(expectVariantToBe('xt'));
});

test('mask', function () {
    Vary::string('Some Text')->mask('*', 0)
        ->tap(expectVariantToBe('*********'));

    Vary::string('Some Text')->mask('*', 4)
        ->tap(expectVariantToBe('Some*****'));

    Vary::string('Some Text')->mask('*', 4, 2)
        ->tap(expectVariantToBe('Some**ext'));
});

test('match', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->match('/One .*? pie/')->tap(expectVariantToBe('One apple pie'));
    $variant->match('/One (.*?) pie/')->tap(expectVariantToBe('apple'));
});

test('override', function () {
    Vary::string('Some text')
        ->override('Hello World')
        ->tap(expectVariantToBe('Hello World'));
});

test('padBoth', function () {
    Vary::string('Some Text')->padBoth(15)
        ->tap(expectVariantToBe('   Some Text   '));

    Vary::string('Some Text')->padBoth(15, '-')
        ->tap(expectVariantToBe('---Some Text---'));

    Vary::string('Some Text')->padBoth(14, '=-')
        ->tap(expectVariantToBe('=-Some Text=-='));
});

test('padLeft', function () {
    Vary::string('Some Text')->padLeft(12)
        ->tap(expectVariantToBe('   Some Text'));

    Vary::string('Some Text')->padLeft(12, '-')
        ->tap(expectVariantToBe('---Some Text'));

    Vary::string('Some Text')->padLeft(12, '=-')
        ->tap(expectVariantToBe('=-=Some Text'));
});

test('padRight', function () {
    Vary::string('Some Text')->padRight(12)
        ->tap(expectVariantToBe('Some Text   '));

    Vary::string('Some Text')->padRight(12, '-')
        ->tap(expectVariantToBe('Some Text---'));

    Vary::string('Some Text')->padRight(12, '=-')
        ->tap(expectVariantToBe('Some Text=-='));
});

test('pipe', function () {
    Vary::string('Some Text')
        ->pipe(fn (Variant $variant) => $variant->override('CHANGED'))
        ->tap(expectVariantToBe('CHANGED'));

    // This is different to the `tap` method which
    // will not propagate the return value.
    Vary::string('Some Text')
        ->tap(fn (Variant $variant) => $variant->override('CHANGED'))
        ->tap(expectVariantToBe('Some Text'));
});

test('plural', function () {
    Vary::string('carrot')->plural()->tap(expectVariantToBe('carrots'));
    Vary::string('carrot')->plural(0)->tap(expectVariantToBe('carrots'));
    Vary::string('carrot')->plural(1)->tap(expectVariantToBe('carrot'));
    Vary::string('potato')->plural()->tap(expectVariantToBe('potatoes'));
    Vary::string('Some Text')->plural()->tap(expectVariantToBe('Some Texts'));
});

test('pluralStudly', function () {
    Vary::string('DeliciousCarrot')->pluralStudly()->tap(expectVariantToBe('DeliciousCarrots'));
    Vary::string('InsanePotato')->pluralStudly()->tap(expectVariantToBe('InsanePotatoes'));
});

test('prepend', function () {
    Vary::string('Some Text')->prepend('I Present You ')
        ->tap(expectVariantToBe('I Present You Some Text'));

    Vary::string('Some Text')->prepend('I ', 'Present ', 'You ')
        ->tap(expectVariantToBe('I Present You Some Text'));

    Vary::string('')->prepend('Foo')
        ->tap(expectVariantToBe('Foo'));
});

test('repeat', function () {
    Vary::string('Some Text ')->repeat(3)
        ->tap(expectVariantToBe('Some Text Some Text Some Text '));

    Vary::string('xo')->repeat(10)
        ->tap(expectVariantToBe('xoxoxoxoxoxoxoxoxoxo'));
});

test('replace', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->replace('One', 'Two')
        ->tap(expectVariantToBe('Two apple pie. Two humble pie. Two apple TV.'));

    $variant->replace(['One', 'pie'], ['Two', 'tarts'])
        ->tap(expectVariantToBe('Two apple tarts. Two humble tarts. Two apple TV.'));
});

test('replaceAll', function () {
    Vary::string('One apple pie. One humble pie. One apple TV.')
        ->replaceAll(['One' => 'Two', 'pie' => 'tarts'])
        ->tap(expectVariantToBe('Two apple tarts. Two humble tarts. Two apple TV.'));
});

test('replaceFirst', function () {
    Vary::string('One apple pie. One humble pie. One apple TV.')
        ->replaceFirst('One', 'Two')
        ->tap(expectVariantToBe('Two apple pie. One humble pie. One apple TV.'));
});

test('replaceLast', function () {
    Vary::string('One apple pie. One humble pie. One apple TV.')
        ->replaceLast('One', 'Two')
        ->tap(expectVariantToBe('One apple pie. One humble pie. Two apple TV.'));
});

test('replaceMatches', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    $variant->replaceMatches('/(pie|TV)/', 'super $1')
        ->tap(expectVariantToBe('One apple super pie. One humble super pie. One apple super TV.'));

    $variant->replaceMatches('/(pie|TV)/', fn (array $matches) => "super $matches[1]")
        ->tap(expectVariantToBe('One apple super pie. One humble super pie. One apple super TV.'));
});

test('replaceSequentially', function () {
    Vary::string('One apple pie. One humble pie. One apple TV.')
        ->replaceSequentially('One', ['Two', 'Three'])
        ->tap(expectVariantToBe('Two apple pie. Three humble pie. One apple TV.'));
});

test('reverse', function () {
    Vary::string('Some Text')->reverse()
        ->tap(expectVariantToBe('txeT emoS'));
});

test('rtrim', function () {
    Vary::string("Some Text   \n  \t ")->rtrim()
        ->tap(expectVariantToBe('Some Text'));

    Vary::string('Some Text ')->rtrim('t ')
        ->tap(expectVariantToBe('Some Tex'));

    Vary::string('Some Text ')->rtrim('Text ')
        ->tap(expectVariantToBe('Som'));
});

test('singular', function () {
    Vary::string('carrots')->singular()->tap(expectVariantToBe('carrot'));
    Vary::string('potatoes')->singular()->tap(expectVariantToBe('potato'));
    Vary::string('candies')->singular()->tap(expectVariantToBe('candy'));
});

test('slug', function () {
    Vary::string('Some Text')->slug()->tap(expectVariantToBe('some-text'));
    Vary::string('Some Text')->slug('[]')->tap(expectVariantToBe('some[]text'));
});

test('snake', function () {
    Vary::string('Some Text')->snake()->tap(expectVariantToBe('some_text'));
    Vary::string('Some Text')->snake('[]')->tap(expectVariantToBe('some[]text'));
    Vary::string('SomeText')->snake()->tap(expectVariantToBe('some_text'));
    Vary::string('some    Text')->snake()->tap(expectVariantToBe('some_text'));
});

test('start', function () {
    Vary::string('Some Text')->start('Some ')
        ->tap(expectVariantToBe('Some Text'));

    Vary::string('Magic Text')->start('Some ')
        ->tap(expectVariantToBe('Some Magic Text'));

    Vary::string('article/42')->start('/')
        ->tap(expectVariantToBe('/article/42'));

    Vary::string('/article/42')->start('/')
        ->tap(expectVariantToBe('/article/42'));
});

test('stripTags', function () {
    Vary::string('<strong>Some Text</strong>')->stripTags()
        ->tap(expectVariantToBe('Some Text'));
});

test('studly', function () {
    Vary::string('Some text')->studly()->tap(expectVariantToBe('SomeText'));
    Vary::string('some-text')->studly()->tap(expectVariantToBe('SomeText'));
    Vary::string('some_text')->studly()->tap(expectVariantToBe('SomeText'));
    Vary::string('someText')->studly()->tap(expectVariantToBe('SomeText'));
});

test('substr', function () {
    Vary::string('Some Text')->substr(5)->tap(expectVariantToBe('Text'));
    Vary::string('Some Text')->substr(0, 4)->tap(expectVariantToBe('Some'));
});

test('substrReplace', function () {
    Vary::string('Some Text')->substrReplace('Carrots', 5)->tap(expectVariantToBe('Some Carrots'));
    Vary::string('Some Text')->substrReplace('More', 0, 4)->tap(expectVariantToBe('More Text'));
});

test('title', function () {
    Vary::string('some text')->title()->tap(expectVariantToBe('Some Text'));
    Vary::string('Some MaGiC text')->title()->tap(expectVariantToBe('Some Magic Text'));
    Vary::string('some-text')->title()->tap(expectVariantToBe('Some-Text'));
    Vary::string('some_text')->title()->tap(expectVariantToBe('Some_Text'));
});

test('trim', function () {
    Vary::string("   \n  \t Some Text   \n  \t ")->trim()
        ->tap(expectVariantToBe('Some Text'));

    Vary::string(' Some Text ')->trim('St ')
        ->tap(expectVariantToBe('ome Tex'));

    Vary::string(' Some Text ')->trim('SomeTt ')
        ->tap(expectVariantToBe('x'));
});

test('ucfirst', function () {
    Vary::string('some text')->ucfirst()
        ->tap(expectVariantToBe('Some text'));
});

test('upper', function () {
    Vary::string('Some Text')->upper()
        ->tap(expectVariantToBe('SOME TEXT'));
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
    Vary::string('Some fairly long sentence with seven words')->words(5)
        ->tap(expectVariantToBe('Some fairly long sentence with...'));

    Vary::string('Some fairly long sentence with seven words')->words(5, ' [redacted]')
        ->tap(expectVariantToBe('Some fairly long sentence with [redacted]'));
});
