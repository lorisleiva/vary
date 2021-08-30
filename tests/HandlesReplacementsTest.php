<?php

use Lorisleiva\Vary\Vary;

it('can empty the entire text', function () {
    $variant = Vary::string('Some text')->empty();

    expect($variant->toString())->toBe('');
});

it('can override the entire text', function () {
    $variant = Vary::string('Some text')->override('Hello World');

    expect($variant->toString())->toBe('Hello World');
});

it('can prepend some text', function () {
    $variant = Vary::string('World')->prepend('Hello ');

    expect($variant->toString())->toBe('Hello World');
});

it('can append some text', function () {
    $variant = Vary::string('World')->append(' Hello');

    expect($variant->toString())->toBe('World Hello');
});

it('can replace all instances of one text with another', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)->replace('day', 'week');

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last week, every week,
        without frenzy, or sloth, or pretense.
    END);
});

it('can replace multiple instances at onces', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $search = ['frenzy', 'sloth', 'pretense'];
    $replace = ['hysteria', 'laziness', 'excuses'];
    $variant = Vary::string($content)->replace($search, $replace);

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last day, every day,
        without hysteria, or laziness, or excuses.
    END);
});

it('can replace multiple instances at onces by providing a key/value array', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)->replaceAll([
        'frenzy' => 'hysteria',
        'sloth' => 'laziness',
        'pretense' => 'excuses',
    ]);

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last day, every day,
        without hysteria, or laziness, or excuses.
    END);
});

it('can replace only the first instance of one text with another', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)->replaceFirst('day', 'week');

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last week, every day,
        without frenzy, or sloth, or pretense.
    END);
});

it('can replace only the last instance of one text with another', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)->replaceLast('day', 'week');

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last day, every week,
        without frenzy, or sloth, or pretense.
    END);
});

it('can replace multiple instances of one text with a sequence of other texts', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)->replaceSequentially('day', ['week', 'minute']);

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last week, every minute,
        without frenzy, or sloth, or pretense.
    END);
});

it('can replace text using regular expressions', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)->replaceMatches('/\s\w+(\sday)/', '$1');

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your day, day,
        without frenzy, or sloth, or pretense.
    END);
});

it('can replace text using regular expressions and a callback', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)->replaceMatches('/\s\w+(\sday)/', function ($matches) {
        return $matches[1];
    });

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your day, day,
        without frenzy, or sloth, or pretense.
    END);
});
