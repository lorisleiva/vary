<?php

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
        ->tap(expectVariantToBe(
            <<<END
            CHANGED
                One humble pie.
            CHANGED
            END
        ));

    $variant->selectLineWithEol('One apple pie.', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe(
            <<<END
            CHANGED    One humble pie.
            CHANGED
            END
        ));
});

it('selects the first and last lines', function () {
    $variant = Vary::string(
        <<<END
        One apple pie.
        One humble pie.
        END
    );

    $variant->selectFirstLine(expectVariantToBe("One apple pie."));
    $variant->selectFirstLineWithEol(expectVariantToBe("One apple pie.\n"));
    $variant->selectLastLine(expectVariantToBe("One humble pie."));
    $variant->selectLastLineWithEol(expectVariantToBe("\nOne humble pie."));
});

it('selects all lines', function () {
    $variant = Vary::string(
        <<<END
        One apple pie.
        One humble pie.
        END
    );

    $variant->selectAllLines(overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe(
            <<<END
            CHANGED
            CHANGED
            END
        ));
});

it('selects lines using regular expressions', function () {
    $variant = Vary::string(
        <<<END
        One apple pie.
        One humble pie.
        One apple TV.
        END
    );

    $variant->selectLinePattern('/^.*TV.*$/', expectVariantToBe('One apple TV.'));
    $variant->selectLinePattern('/^.*TV.*$/m', expectVariantToBe('One apple TV.'));
    $variant->selectLinePattern('/^.*TV.*$\n?/m', expectVariantToBe('One apple TV.'));
    $variant->selectLinePattern('/^.*humble.*$\n?/m', expectVariantToBe("One humble pie.\n"));
    $variant->selectLinePattern('/\n?^.*humble.*$/m', expectVariantToBe("\nOne humble pie."));

    $variant->selectLinePattern('/^.*apple.*$/', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe(
            <<<END
            CHANGED
            One humble pie.
            CHANGED
            END
        ));
});

it('prepends some lines', function () {
    Vary::string(
        <<<END
                One apple pie.
                One humble pie.
            END
    )
        ->prependLine(
            <<<END
            Two apple pie.
            Two humble pie.
            END
        )->tap(expectVariantToBe(
            <<<END
            Two apple pie.
            Two humble pie.
                One apple pie.
                One humble pie.
            END
        ));
});

it('prepends some lines whilst keeping the indentation of the first line', function () {
    Vary::string(
        <<<END
                One apple pie.
                One humble pie.
            END
    )
        ->prependLine(
            <<<END
            Two apple pie.
            Two humble pie.
            END,
            keepIndent: true,
        )->tap(expectVariantToBe(
            <<<END
                Two apple pie.
                Two humble pie.
                One apple pie.
                One humble pie.
            END
        ));
});

it('appends some lines', function () {
    Vary::string(
        <<<END
                One apple pie.
                One humble pie.
            END
    )
        ->appendLine(
            <<<END
            Two apple pie.
            Two humble pie.
            END
        )->tap(expectVariantToBe(
            <<<END
                One apple pie.
                One humble pie.
            Two apple pie.
            Two humble pie.
            END
        ));
});

it('appends some lines whilst keeping the indentation of the first line', function () {
    Vary::string(
        <<<END
                One apple pie.
                One humble pie.
            END
    )
        ->appendLine(
            <<<END
            Two apple pie.
            Two humble pie.
            END,
            keepIndent: true,
        )->tap(expectVariantToBe(
            <<<END
                One apple pie.
                One humble pie.
                Two apple pie.
                Two humble pie.
            END
        ));
});

it('ignores indentation and line jumps when the text is empty', function () {
    Vary::string('')->appendLine('New Line', keepIndent: true)
       ->tap(expectVariantToBe('New Line'));

    Vary::string('')->prependLine('New Line', keepIndent: true)
       ->tap(expectVariantToBe('New Line'));
});

// it('can add a line after another line', function () {
//     $content = <<<END
//         Perfection of character: to live your last day, every day,
//         without frenzy, or sloth, or pretense.
//     END;
//
//     $variant = Vary::string($content)
//         ->addAfterLine('Perfection of character: to live your last day, every day,', 'New Line');
//
//     expect($variant->toString())->toBe(<<<END
//         Perfection of character: to live your last day, every day,
//     New Line
//         without frenzy, or sloth, or pretense.
//     END);
// });
//
// it('can add a line after another line whilst keeping its identation', function () {
//     $content = <<<END
//         Perfection of character: to live your last day, every day,
//         without frenzy, or sloth, or pretense.
//     END;
//
//     $variant = Vary::string($content)
//         ->addAfterLine('Perfection of character: to live your last day, every day,', 'New Line', keepIndent: true);
//
//     expect($variant->toString())->toBe(<<<END
//         Perfection of character: to live your last day, every day,
//         New Line
//         without frenzy, or sloth, or pretense.
//     END);
// });
//
// it('can add a line after another matched line', function () {
//     $content = <<<END
//         Perfection of sloth: to live your last day, every day,
//         without frenzy, or sloth, or pretense.
//     END;
//
//     $variant = Vary::string($content)
//         ->addAfterLinePattern('sloth', 'New Line', keepIndent: true);
//
//     expect($variant->toString())->toBe(<<<END
//         Perfection of sloth: to live your last day, every day,
//         New Line
//         without frenzy, or sloth, or pretense.
//         New Line
//     END);
// });
//
// it('can add a line before another line', function () {
//     $content = <<<END
//         Perfection of character: to live your last day, every day,
//         without frenzy, or sloth, or pretense.
//     END;
//
//     $variant = Vary::string($content)
//         ->addBeforeLine('without frenzy, or sloth, or pretense.', 'New Line');
//
//     expect($variant->toString())->toBe(<<<END
//         Perfection of character: to live your last day, every day,
//     New Line
//         without frenzy, or sloth, or pretense.
//     END);
// });
//
// it('can add a line before another line whilst keeping its identation', function () {
//     $content = <<<END
//         Perfection of character: to live your last day, every day,
//         without frenzy, or sloth, or pretense.
//     END;
//
//     $variant = Vary::string($content)
//         ->addBeforeLine('without frenzy, or sloth, or pretense.', 'New Line', keepIndent: true);
//
//     expect($variant->toString())->toBe(<<<END
//         Perfection of character: to live your last day, every day,
//         New Line
//         without frenzy, or sloth, or pretense.
//     END);
// });
//
// it('can add a line before another matched line', function () {
//     $content = <<<END
//         Perfection of sloth: to live your last day, every day,
//         without frenzy, or sloth, or pretense.
//     END;
//
//     $variant = Vary::string($content)
//         ->addBeforeLinePattern('sloth', 'New Line', keepIndent: true);
//
//     expect($variant->toString())->toBe(<<<END
//         New Line
//         Perfection of sloth: to live your last day, every day,
//         New Line
//         without frenzy, or sloth, or pretense.
//     END);
// });
//
// it('can remove the first line', function () {
//     $content = <<<END
//         Perfection of character: to live your last day, every day,
//         without frenzy, or sloth, or pretense.
//     END;
//
//     $variant = Vary::string($content)->deleteFirstLine();
//
//     expect($variant->toString())->toBe(<<<END
//         without frenzy, or sloth, or pretense.
//     END);
// });
//
// it('can remove the last line', function () {
//     $content = <<<END
//         Perfection of character: to live your last day, every day,
//         without frenzy, or sloth, or pretense.
//     END;
//
//     $variant = Vary::string($content)->deleteLastLine();
//
//     expect($variant->toString())->toBe(<<<END
//         Perfection of character: to live your last day, every day,
//     END);
// });
//
// it('can remove lines that matches the given text exactly', function () {
//     $content = <<<END
//         First Line
//         Second Line
//         Third Line
//     END;
//
//     expect(Vary::string($content)->deleteLine('First Line')->toString())
//         ->toBe(<<<END
//             Second Line
//             Third Line
//         END);
//
//     expect(Vary::string($content)->deleteLine('Second Line')->toString())
//         ->toBe(<<<END
//             First Line
//             Third Line
//         END);
//
//     expect(Vary::string($content)->deleteLine('Third Line')->toString())
//         ->toBe(<<<END
//             First Line
//             Second Line
//         END);
//
//     expect(Vary::string('One line only')->deleteLine('One line only')->toString())
//         ->toBe('');
// });
//
// it('can remove lines that matches a given regex', function () {
//     $content = <<<END
//         First Line
//         Second Line
//         Third Line
//     END;
//
//     expect(Vary::string($content)->deleteLinePattern('First')->toString())
//         ->toBe(<<<END
//             Second Line
//             Third Line
//         END);
//
//     expect(Vary::string($content)->deleteLinePattern('Second')->toString())
//         ->toBe(<<<END
//             First Line
//             Third Line
//         END);
//
//     expect(Vary::string($content)->deleteLinePattern('Third')->toString())
//         ->toBe(<<<END
//             First Line
//             Second Line
//         END);
//
//     expect(Vary::string('One line only')->deleteLinePattern('only')->toString())
//         ->toBe('');
// });
