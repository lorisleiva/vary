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

it('appends some lines whilst keeping the indentation of the last line', function () {
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

it('adds some lines before other lines', function () {
    $variant = Vary::string(
        <<<END
            One apple pie.
            One humble pie.
        END
    );

    $variant
        ->addBeforeLine(
            search: 'One humble pie.',
            content: <<<END
            Two apple pie.
            Two humble pie.
            END
        )
        ->tap(expectVariantToBe(
            <<<END
                One apple pie.
            Two apple pie.
            Two humble pie.
                One humble pie.
            END
        ));
});

it('adds some lines before other lines whilst keeping its indentation', function () {
    $variant = Vary::string(
        <<<END
            One apple pie.
                One humble pie.
        END
    );

    $variant
        ->addBeforeLine(
            search: 'One humble pie.',
            content: <<<END
            Two apple pie.
            Two humble pie.
            END,
            keepIndent: true,
        )
        ->tap(expectVariantToBe(
            <<<END
                One apple pie.
                    Two apple pie.
                    Two humble pie.
                    One humble pie.
            END
        ));
});

it('adds some lines before other lines using regular expressions', function () {
    $variant = Vary::string(
        <<<END
            One apple pie.
            One humble pie.
        END
    );

    $variant
        ->addBeforeLinePattern(
            pattern: '/^.*pie.*$/',
            content: 'NEW LINE',
            keepIndent: true,
        )
        ->tap(expectVariantToBe(
            <<<END
                NEW LINE
                One apple pie.
                NEW LINE
                One humble pie.
            END
        ));
});

it('adds some lines after other lines', function () {
    $variant = Vary::string(
        <<<END
            One apple pie.
            One humble pie.
        END
    );

    $variant
        ->addAfterLine(
            search: 'One apple pie.',
            content: <<<END
            Two apple pie.
            Two humble pie.
            END
        )
        ->tap(expectVariantToBe(
            <<<END
                One apple pie.
            Two apple pie.
            Two humble pie.
                One humble pie.
            END
        ));
});

it('adds some lines after other lines whilst keeping its indentation', function () {
    $variant = Vary::string(
        <<<END
            One apple pie.
                One humble pie.
        END
    );

    $variant
        ->addAfterLine(
            search: 'One apple pie.',
            content: <<<END
            Two apple pie.
            Two humble pie.
            END,
            keepIndent: true,
        )
        ->tap(expectVariantToBe(
            <<<END
                One apple pie.
                Two apple pie.
                Two humble pie.
                    One humble pie.
            END
        ));
});

it('adds some lines after other lines using regular expressions', function () {
    $variant = Vary::string(
        <<<END
            One apple pie.
            One humble pie.
        END
    );

    $variant
        ->addAfterLinePattern(
            pattern: '/^.*pie.*$/',
            content: 'NEW LINE',
            keepIndent: true,
        )
        ->tap(expectVariantToBe(
            <<<END
                One apple pie.
                NEW LINE
                One humble pie.
                NEW LINE
            END
        ));
});

it('deletes lines by providing their content', function () {
    $variant = Vary::string(<<<END
        One apple pie.
        One humble pie.
        One apple pie.
    END);

    $variant->deleteLine('One apple pie.')
        ->tap(expectVariantToBe('    One humble pie.'));

    $variant->deleteLine('    One apple pie.', ignoreWhitespace: false)
        ->tap(expectVariantToBe('    One humble pie.'));
});

it('deletes the EOL accordingly', function () {
    $variant = Vary::string(
        <<<END
        First line.
        Second line.
        Last line.
        END
    );

    $variant->deleteLine('First line.')
        ->tap(expectVariantToBe("Second line.\nLast line."));

    $variant->deleteLine('Second line.')
        ->tap(expectVariantToBe("First line.\nLast line."));

    $variant->deleteLine('Last line.')
        ->tap(expectVariantToBe("First line.\nSecond line."));

    Vary::string('One line only.')
        ->deleteLine('One line only.')
        ->tap(expectVariantToBe(''));
});

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

it('deletes lines using regular expressions', function () {
    $variant = Vary::string(<<<END
        One apple pie.
        One humble pie.
        One apple TV.
    END);

    $variant->deleteLinePattern('/^.*pie.*$/')
        ->tap(expectVariantToBe('    One apple TV.'));
});

it('deletes the EOL accordingly using regular expressions', function () {
    $variant = Vary::string(
        <<<END
        First line.
        Second line.
        Last line.
        END
    );

    $variant->deleteLinePattern('/^First.*$/')
        ->tap(expectVariantToBe("Second line.\nLast line."));

    $variant->deleteLinePattern('/^Second.*$/')
        ->tap(expectVariantToBe("First line.\nLast line."));

    $variant->deleteLinePattern('/^Last.*$/')
        ->tap(expectVariantToBe("First line.\nSecond line."));

    Vary::string('One line only.')
        ->deleteLinePattern('/^.*$/')
        ->tap(expectVariantToBe(''));
});
