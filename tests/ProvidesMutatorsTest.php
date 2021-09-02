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
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)->replacePattern('/\s\w+(\sday)/', '$1');

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your day, day,
        without frenzy, or sloth, or pretense.
    END);
});

it('replaces text using regular expressions and a callback', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)->replacePattern('/\s\w+(\sday)/', function ($matches) {
        return $matches[1];
    });

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your day, day,
        without frenzy, or sloth, or pretense.
    END);
});

it('can delete all instances of one text', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    expect(Vary::string($content)->delete(' day')->toString())->toBe(<<<END
        Perfection of character: to live your last, every,
        without frenzy, or sloth, or pretense.
    END);

    expect(Vary::string($content)->delete([' day', ' or'])->toString())->toBe(<<<END
        Perfection of character: to live your last, every,
        without frenzy, sloth, pretense.
    END);
});

it('can delete the first or last instance of one text', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    expect(Vary::string($content)->deleteFirst(' day')->toString())->toBe(<<<END
        Perfection of character: to live your last, every day,
        without frenzy, or sloth, or pretense.
    END);

    expect(Vary::string($content)->deleteLast(' day')->toString())->toBe(<<<END
        Perfection of character: to live your last day, every,
        without frenzy, or sloth, or pretense.
    END);
});

it('can delete instances of text that match a given regex', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    expect(Vary::string($content)->deletePattern('/\s(day|or)/')->toString())->toBe(<<<END
        Perfection of character: to live your last, every,
        without frenzy, sloth, pretense.
    END);
});
