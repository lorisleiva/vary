<?php

use Lorisleiva\Vary\Vary;

it('can replace all instances of a mustache variable with the given value', function () {
    $content = <<<END
        Perfection of character: to live your last {{ unit }}, every {{ unit }},
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)->replaceMustache('unit', 'day');

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END);
});

it('can replace multiple mustache variables within a given array of data', function () {
    $content = <<<END
        Perfection of {{ entity }}: to live your last {{ unit }}, every {{ unit }},
        without frenzy, {{ gate }} sloth, {{ gate }} pretense.
    END;

    $variant = Vary::string($content)->replaceAllMustaches([
        'entity' => 'self',
        'unit' => 'minute',
        'gate' => 'nor',
    ]);

    expect($variant->toString())->toBe(<<<END
        Perfection of self: to live your last minute, every minute,
        without frenzy, nor sloth, nor pretense.
    END);
});

it('can replace mustache variable no matter how many whitespace is provided', function () {
    expect(Vary::string('{{unit}}')->replaceMustache('unit', 'day')->toString())->toBe('day');
    expect(Vary::string('{{ unit}}')->replaceMustache('unit', 'day')->toString())->toBe('day');
    expect(Vary::string('{{unit }}')->replaceMustache('unit', 'day')->toString())->toBe('day');
    expect(Vary::string('{{ unit }}')->replaceMustache('unit', 'day')->toString())->toBe('day');
    expect(Vary::string('{{      unit      }}')->replaceMustache('unit', 'day')->toString())->toBe('day');
    expect(Vary::string("{{ unit \t\n\r}}")->replaceMustache('unit', 'day')->toString())->toBe('day');
});
