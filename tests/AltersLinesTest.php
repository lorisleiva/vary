<?php

use Lorisleiva\Vary\Variant;
use Lorisleiva\Vary\Vary;

it('selects a line by providing its content', function () {
    $variant = Vary::string(<<<END
        One apple pie.
        One humble pie.
        One apple TV.
    END);

    // Select lines ignoring left and right whitespace.
    $variant->selectLine('One apple pie.', expectVariantToBe("    One apple pie."));
    $variant->selectLineWithEol('One apple pie.', expectVariantToBe("    One apple pie.\n"));
    $variant->selectLineWithEol('One apple TV.', expectVariantToBe("    One apple TV."));

    // Select lines including whitespaces.
    $variant->selectExactLine('One apple pie.', expectVariantNotToBeCalled());
    $variant->selectExactLine('    One apple pie.', expectVariantToBe("    One apple pie."));
    $variant->selectExactLineWithEol('    One apple pie.', expectVariantToBe("    One apple pie.\n"));
    $variant->selectExactLineWithEol('    One apple TV.', expectVariantToBe("    One apple TV."));
});

it('selects multiple lines by providing their content', function () {
    $variant = Vary::string(<<<END
        One apple pie.
        One humble pie.
        One apple pie.
    END);

    $variant->selectLine('One apple pie.', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe(<<<END
            CHANGED
                One humble pie.
            CHANGED
            END
        ));

    $variant->selectLineWithEol('One apple pie.', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe(<<<END
            CHANGED    One humble pie.
            CHANGED
            END
        ));
});

it('can select the first line', function () {
    $content = <<<END
        Hello
        Hello
        END;

    $variant = Vary::string($content)
        ->selectFirstLine(fn (Variant $variant) => $variant->replace('Hello', 'World'));

    expect($variant->toString())->toBe(<<<END
        World
        Hello
        END);
});

it('can select the last line', function () {
    $content = <<<END
        Hello
        Hello
    END;

    $variant = Vary::string($content)
        ->selectLastLine(fn (Variant $variant) => $variant->replace('Hello', 'World'));

    expect($variant->toString())->toBe(<<<END
        Hello
        World
    END);
});

it('can update lines from regex expressions', function () {
    $content = <<<END
        Perfection of sloth: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)
        ->selectLinePattern('frenzy', fn (Variant $variant) => $variant->replace('sloth', 'laziness'));

    expect($variant->toString())->toBe(<<<END
        Perfection of sloth: to live your last day, every day,
        without frenzy, or laziness, or pretense.
    END);
});

it('can update lines from regex expressions including the end of line', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)
        ->selectLinePattern('character', fn (Variant $variant) => $variant->empty(), includeEol: true);

    expect($variant->toString())->toBe(<<<END
        without frenzy, or sloth, or pretense.
    END);
});

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
        ->addLineAfterPattern('sloth', 'New Line', keepIndent: true);

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
        ->addLineBeforePattern('sloth', 'New Line', keepIndent: true);

    expect($variant->toString())->toBe(<<<END
        New Line
        Perfection of sloth: to live your last day, every day,
        New Line
        without frenzy, or sloth, or pretense.
    END);
});

it('can remove the first line', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)->deleteFirstLine();

    expect($variant->toString())->toBe(<<<END
        without frenzy, or sloth, or pretense.
    END);
});

it('can remove the last line', function () {
    $content = <<<END
        Perfection of character: to live your last day, every day,
        without frenzy, or sloth, or pretense.
    END;

    $variant = Vary::string($content)->deleteLastLine();

    expect($variant->toString())->toBe(<<<END
        Perfection of character: to live your last day, every day,
    END);
});

it('can remove lines that matches the given text exactly', function () {
    $content = <<<END
        First Line
        Second Line
        Third Line
    END;

    expect(Vary::string($content)->deleteLine('First Line')->toString())
        ->toBe(<<<END
            Second Line
            Third Line
        END);

    expect(Vary::string($content)->deleteLine('Second Line')->toString())
        ->toBe(<<<END
            First Line
            Third Line
        END);

    expect(Vary::string($content)->deleteLine('Third Line')->toString())
        ->toBe(<<<END
            First Line
            Second Line
        END);

    expect(Vary::string('One line only')->deleteLine('One line only')->toString())
        ->toBe('');
});

it('can remove lines that matches a given regex', function () {
    $content = <<<END
        First Line
        Second Line
        Third Line
    END;

    expect(Vary::string($content)->deleteLinePattern('First')->toString())
        ->toBe(<<<END
            Second Line
            Third Line
        END);

    expect(Vary::string($content)->deleteLinePattern('Second')->toString())
        ->toBe(<<<END
            First Line
            Third Line
        END);

    expect(Vary::string($content)->deleteLinePattern('Third')->toString())
        ->toBe(<<<END
            First Line
            Second Line
        END);

    expect(Vary::string('One line only')->deleteLinePattern('only')->toString())
        ->toBe('');
});
