<?php

use Lorisleiva\Vary\Vary;

it('can append a line at the end', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)->appendLine('— Marcus Aurelius');

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    — Marcus Aurelius
    END);
});

it('can append a line at the end whilst keeping the last identation', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)->appendLine('— Marcus Aurelius', keepIndent: true);

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
        — Marcus Aurelius
    END);
});

it('can prepend a line at the end', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)->prependLine('Marcus Aurelius said:');

    expect($variant->toString())->toBe(<<<END
    Marcus Aurelius said:
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END);
});

it('can prepend a line at the end whilst keeping the last identation', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)->prependLine('Marcus Aurelius said:', keepIndent: true);

    expect($variant->toString())->toBe(<<<END
        Marcus Aurelius said:
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END);
});

it('ignores identation when the text is empty', function () {
    expect(Vary::string('')->appendLine('New Line', keepIndent: true)->toString())
        ->toBe('New Line');

    expect(Vary::string('')->prependLine('New Line', keepIndent: true)->toString())
        ->toBe('New Line');
});
