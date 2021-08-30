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

it('keeps the before text intact when updating an after fragment', function () {
    $variant = Vary::string('Some repeated fragment. Some repeated fragment.')
        ->after('fragment', fn (Variant $variant) => $variant->replace('repeated', 'unique'));

    expect($variant->toString())
        ->toBe('Some repeated fragment. Some unique fragment.');
});
