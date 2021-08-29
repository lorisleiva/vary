<?php

use Lorisleiva\Vary\Vary;

it('can replace all instances of one text with another', function () {

    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    expect(Vary::string($content)->replace('day', 'week')->toString())->toBe(<<<END
        Perfection of character: to live your last week, every week,
        without frenzy, or sloth, or pretense.
    END);
});
