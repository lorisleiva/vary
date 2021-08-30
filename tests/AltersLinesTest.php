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

it('can add a line after another line', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)
        ->addLineAfter('Perfection of character: to live your last day, every day,', 'New Line');

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last day, every day,
    New Line
        without frenzy, or sloth, or pretense.
    END);
});

it('can add a line after another line whilst keeping its identation', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)
        ->addLineAfter('Perfection of character: to live your last day, every day,', 'New Line', keepIndent: true);

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last day, every day,
        New Line
        without frenzy, or sloth, or pretense.
    END);
});

it('can add a line after another matched line', function () {
    $content = <<<END
        Perfection of sloth: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)
        ->addLineAfterMatches('sloth', 'New Line', keepIndent: true);

    expect($variant->toString())->toBe(<<<END
        Perfection of sloth: to live your last day, every day,
        New Line
        without frenzy, or sloth, or pretense.
        New Line
    END);
});

it('can add a line before another line', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)
        ->addLineBefore('without frenzy, or sloth, or pretense.', 'New Line');

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last day, every day,
    New Line
        without frenzy, or sloth, or pretense.
    END);
});

it('can add a line before another line whilst keeping its identation', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)
        ->addLineBefore('without frenzy, or sloth, or pretense.', 'New Line', keepIndent: true);

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last day, every day,
        New Line
        without frenzy, or sloth, or pretense.
    END);
});

it('can add a line before another matched line', function () {
    $content = <<<END
        Perfection of sloth: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)
        ->addLineBeforeMatches('sloth', 'New Line', keepIndent: true);

    expect($variant->toString())->toBe(<<<END
        New Line
        Perfection of sloth: to live your last day, every day,
        New Line
        without frenzy, or sloth, or pretense.
    END);
});

it('can remove the first line', function () {
    $content = <<<END
        Perfection of sloth: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)->removeFirstLine();

    expect($variant->toString())->toBe(<<<END
        without frenzy, or sloth, or pretense.
    END);
});

it('can remove the last line', function () {
    $content = <<<END
        Perfection of sloth: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)->removeLastLine();

    expect($variant->toString())->toBe(<<<END
        Perfection of sloth: to live your last day, every day,
    END);
});
