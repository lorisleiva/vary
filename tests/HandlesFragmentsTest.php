<?php

use Lorisleiva\Vary\Variant;
use Lorisleiva\Vary\Vary;

it('can tap its own value', function () {
    $variant = Vary::string('Hello World')
        ->tap(fn (Variant $variant) => $variant->replace('World', 'Moto'));

    expect($variant->toString())->toBe('Hello Moto');
});

it('can update a fragment before a given text', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)
        ->selectBefore('every', fn (Variant $variant) => $variant->replace('day', 'week'));

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
        ->selectBeforeLast('day', fn (Variant $variant) => $variant->replace('every', 'any'));

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last day, any day,
        without frenzy, or sloth, or pretense.
    END);
});

it('keeps the after text intact when updating a before fragment', function () {
    $variant = Vary::string('Some repeated fragment. Some repeated fragment.')
        ->selectBefore('fragment', fn (Variant $variant) => $variant->replace('repeated', 'unique'));

    expect($variant->toString())
        ->toBe('Some unique fragment. Some repeated fragment.');
});

it('can update a fragment after a given text', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)
        ->selectAfter('every', fn (Variant $variant) => $variant->replace('day', 'week'));

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
        ->selectAfterLast('day', fn (Variant $variant) => $variant->replace('sloth', 'laziness'));

    expect($variant->toString())->toBe(<<<END
        Perfection of sloth: to live your last day, every day,
        without frenzy, or laziness, or pretense.
    END);
});

it('keeps the before text intact when updating an after fragment', function () {
    $variant = Vary::string('Some repeated fragment. Some repeated fragment.')
        ->selectAfter('fragment', fn (Variant $variant) => $variant->replace('repeated', 'unique'));

    expect($variant->toString())
        ->toBe('Some repeated fragment. Some unique fragment.');
});

it('can include the before and after text when updating a fragment', function () {
    $content = 'last day, every day';
    $callback = fn (Variant $variant) => $variant->replace('day', 'week');

    // Before.
    expect(Vary::string($content)->selectBeforeIncluded('day', $callback)->toString())
        ->toBe('last week, every day');
    expect(Vary::string($content)->selectBeforeLastIncluded('day', $callback)->toString())
        ->toBe('last week, every week');

    // After.
    expect(Vary::string($content)->selectAfterIncluded('day', $callback)->toString())
        ->toBe('last week, every week');
    expect(Vary::string($content)->selectAfterLastIncluded('day', $callback)->toString())
        ->toBe('last day, every week');
});

it('ignores the nested variants when the before or after text was not found', function () {
    $callback = fn (Variant $variant) => $variant->append('CHANGED');

    expect(Vary::string('')->selectBefore('Some text', $callback)->toString())->toBe('');
    expect(Vary::string('')->selectAfter('Some text', $callback)->toString())->toBe('');
});

it('can update a fragment between two given text', function () {
    $content = 'Some repeated fragment. Some repeated fragment. Some repeated fragment.';
    $callback = fn (Variant $variant) => $variant->replace('repeated', 'CHANGED');

    // Between first and first.
    expect(Vary::string($content)->selectBetween('Some', 'fragment', $callback)->toString())
        ->toBe('Some CHANGED fragment. Some repeated fragment. Some repeated fragment.');
    expect(Vary::string($content)->selectBetweenFirstAndFirst('Some', 'fragment', $callback)->toString())
        ->toBe('Some CHANGED fragment. Some repeated fragment. Some repeated fragment.');
    expect(Vary::string($content)->selectBetweenFirstAndFirstIncluded('repeated', 'repeated', $callback)->toString())
        ->toBe('Some CHANGED fragment. Some repeated fragment. Some repeated fragment.');

    // Between first and last.
    expect(Vary::string($content)->selectBetweenFirstAndLast('Some', 'fragment', $callback)->toString())
        ->toBe('Some CHANGED fragment. Some CHANGED fragment. Some CHANGED fragment.');
    expect(Vary::string($content)->selectBetweenFirstAndLastIncluded('repeated', 'repeated', $callback)->toString())
        ->toBe('Some CHANGED fragment. Some CHANGED fragment. Some CHANGED fragment.');

    // Between last and first.
    expect(Vary::string($content)->selectBetweenLastAndFirst('Some', 'fragment', $callback)->toString())
        ->toBe('Some repeated fragment. Some repeated fragment. Some CHANGED fragment.');
    expect(Vary::string($content)->selectBetweenLastAndFirstIncluded('repeated', 'repeated', $callback)->toString())
        ->toBe('Some repeated fragment. Some repeated fragment. Some CHANGED fragment.');

    // Between last and last.
    expect(Vary::string($content)->selectBetweenLastAndLast('Some', 'fragment', $callback)->toString())
        ->toBe('Some repeated fragment. Some repeated fragment. Some CHANGED fragment.');
    expect(Vary::string($content)->selectBetweenLastAndLastIncluded('repeated', 'repeated', $callback)->toString())
        ->toBe('Some repeated fragment. Some repeated fragment. Some CHANGED fragment.');
});

it('can update fragments from a regex expression', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)
        ->selectPattern('/day/', fn (Variant $variant) => $variant->replace('day', 'week'));

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
        ->selectPatternFirstGroup('/character(.*)every/', fn (Variant $variant) => $variant->replace('day', 'week'));

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last week, every day,
        without frenzy, or sloth, or pretense.
    END);
});

it('can update fragments before, after and between whitespaces', function () {
    $content = "  \t\n  \r\n Hello Moto \n  \t";
    $callback = fn (Variant $variant) => $variant->override('CHANGED');

    expect(Vary::string($content)->selectBeforeWhitespace($callback)->toString())
        ->toBe("CHANGED \n  \t");

    expect(Vary::string($content)->selectAfterWhitespace($callback)->toString())
        ->toBe("  \t\n  \r\n CHANGED");

    expect(Vary::string($content)->selectBetweenWhitespace($callback)->toString())
        ->toBe("  \t\n  \r\n CHANGED \n  \t");
});
