<?php

use Lorisleiva\Vary\Variant;
use Lorisleiva\Vary\Vary;

it('can update a fragment before a given text', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)
        ->before('every', fn (Variant $variant) => $variant->replace('day', 'week'));

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last week, every day,
        without frenzy, or sloth, or pretense.
    END);
});

it('can update a fragment before the last instance a given text', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)
        ->beforeLast('day', fn (Variant $variant) => $variant->replace('every', 'any'));

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last day, any day,
        without frenzy, or sloth, or pretense.
    END);
});

it('keeps the after text intact when updating a before fragment', function () {
    $variant = Vary::string('Some repeated fragment. Some repeated fragment.')
        ->before('fragment', fn (Variant $variant) => $variant->replace('repeated', 'unique'));

    expect($variant->toString())
        ->toBe('Some unique fragment. Some repeated fragment.');
});

it('can update a fragment after a given text', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)
        ->after('every', fn (Variant $variant) => $variant->replace('day', 'week'));

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last day, every week,
        without frenzy, or sloth, or pretense.
    END);
});

it('can update a fragment after the last instance of a given text', function () {
    $content = <<<END
        Perfection of sloth: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)
        ->afterLast('day', fn (Variant $variant) => $variant->replace('sloth', 'laziness'));

    expect($variant->toString())->toBe(<<<END
        Perfection of sloth: to live your last day, every day,
        without frenzy, or laziness, or pretense.
    END);
});

it('keeps the before text intact when updating an after fragment', function () {
    $variant = Vary::string('Some repeated fragment. Some repeated fragment.')
        ->after('fragment', fn (Variant $variant) => $variant->replace('repeated', 'unique'));

    expect($variant->toString())
        ->toBe('Some repeated fragment. Some unique fragment.');
});

it('can include the before and after text when updating a fragment', function () {
    $content = 'last day, every day';
    $callback = fn (Variant $variant) => $variant->replace('day', 'week');

    // Before.
    expect(Vary::string($content)->beforeIncluded('day', $callback)->toString())
        ->toBe('last week, every day');
    expect(Vary::string($content)->beforeLastIncluded('day', $callback)->toString())
        ->toBe('last week, every week');

    // After.
    expect(Vary::string($content)->afterIncluded('day', $callback)->toString())
        ->toBe('last week, every week');
    expect(Vary::string($content)->afterLastIncluded('day', $callback)->toString())
        ->toBe('last day, every week');
});

it('can update a fragment between two given text', function () {
    $content = 'Some repeated fragment. Some repeated fragment. Some repeated fragment.';
    $callback = fn (Variant $variant) => $variant->replace('repeated', 'CHANGED');

    // Between first and first.
    expect(Vary::string($content)->between('Some', 'fragment', $callback)->toString())
        ->toBe('Some CHANGED fragment. Some repeated fragment. Some repeated fragment.');
    expect(Vary::string($content)->betweenFirstAndFirst('Some', 'fragment', $callback)->toString())
        ->toBe('Some CHANGED fragment. Some repeated fragment. Some repeated fragment.');
    expect(Vary::string($content)->betweenFirstAndFirstIncluded('repeated', 'repeated', $callback)->toString())
        ->toBe('Some CHANGED fragment. Some repeated fragment. Some repeated fragment.');

    // Between first and last.
    expect(Vary::string($content)->betweenFirstAndLast('Some', 'fragment', $callback)->toString())
        ->toBe('Some CHANGED fragment. Some CHANGED fragment. Some CHANGED fragment.');
    expect(Vary::string($content)->betweenFirstAndLastIncluded('repeated', 'repeated', $callback)->toString())
        ->toBe('Some CHANGED fragment. Some CHANGED fragment. Some CHANGED fragment.');

    // Between last and first.
    expect(Vary::string($content)->betweenLastAndFirst('Some', 'fragment', $callback)->toString())
        ->toBe('Some repeated fragment. Some repeated fragment. Some CHANGED fragment.');
    expect(Vary::string($content)->betweenLastAndFirstIncluded('repeated', 'repeated', $callback)->toString())
        ->toBe('Some repeated fragment. Some repeated fragment. Some CHANGED fragment.');

    // Between last and last.
    expect(Vary::string($content)->betweenLastAndLast('Some', 'fragment', $callback)->toString())
        ->toBe('Some repeated fragment. Some repeated fragment. Some CHANGED fragment.');
    expect(Vary::string($content)->betweenLastAndLastIncluded('repeated', 'repeated', $callback)->toString())
        ->toBe('Some repeated fragment. Some repeated fragment. Some CHANGED fragment.');
});

it('can update fragments from a regex expression', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)
        ->match('/day/', fn (Variant $variant) => $variant->replace('day', 'week'));

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last week, every week,
        without frenzy, or sloth, or pretense.
    END);
});

it('can update fragments from the first captured group of a regex expression', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)
        ->matchFirstGroup('/character(.*)every/', fn (Variant $variant) => $variant->replace('day', 'week'));

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last week, every day,
        without frenzy, or sloth, or pretense.
    END);
});

it('can update lines from regex expressions', function () {
    $content = <<<END
        Perfection of sloth: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)
        ->matchLine('frenzy', fn (Variant $variant) => $variant->replace('sloth', 'laziness'));

    expect($variant->toString())->toBe(<<<END
        Perfection of sloth: to live your last day, every day,
        without frenzy, or laziness, or pretense.
    END);
});
