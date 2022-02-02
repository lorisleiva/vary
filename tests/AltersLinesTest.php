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
    $variant->selectLineWithEol('One apple TV.', expectVariantToBe("\n    One apple TV."));

    // Select lines including whitespaces.
    $variant->selectExactLine('One apple pie.', expectVariantNotToBeCalled());
    $variant->selectExactLine('    One apple pie.', expectVariantToBe("    One apple pie."));
    $variant->selectExactLineWithEol('    One apple pie.', expectVariantToBe("    One apple pie.\n"));
    $variant->selectExactLineWithEol('    One apple TV.', expectVariantToBe("\n    One apple TV."));
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
            CHANGED    One humble pie.CHANGED
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

    $variant->selectAllLinesWithEol(overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe('CHANGEDCHANGED'));
});

it('selects lines using regular expressions', function () {
    $variant = Vary::string(
        <<<END
        One apple pie.
        One humble pie.
        One apple TV.
        END
    );

    $variant->selectLineMatches('.*TV.*', expectVariantToBe('One apple TV.'));
    $variant->selectLineMatchesWithEol('.*TV.*', expectVariantToBe("\nOne apple TV."));
    $variant->selectLineMatches('.*humble.*', expectVariantToBe('One humble pie.'));
    $variant->selectLineMatchesWithEol('.*humble.*', expectVariantToBe("One humble pie.\n"));
    $variant->selectLineMatches('.*apple.*', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe(
            <<<END
            CHANGED
            One humble pie.
            CHANGED
            END
        ));

    // It has to match the entire line!
    $variant->selectLineMatches('TV', expectVariantNotToBeCalled());
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
        ->addBeforeLineMatches(
            pattern: '.*pie.*',
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
        ->addAfterLineMatches(
            pattern: '.*pie.*',
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

it('deletes multiple lines at the same time', function () {
    $variant = Vary::string(<<<END
        First line.
        Second line.
        Last line.
    END);

    $variant->deleteLines(['First line.', 'Second line.'])
        ->tap(expectVariantToBe('    Last line.'));
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

    Vary::string("First line.\nSecond line.\n")
        ->deleteLine('Second line.')
        ->tap(expectVariantToBe("First line.\n"));

    Vary::string("First line.\nSecond line.\n")
        ->deleteLine('First line.')
        ->tap(expectVariantToBe("Second line.\n"));
});

it('removes the first line', function () {
    $variant = Vary::string(
        <<<END
        First line.
        Last line.
        END
    );

    $variant->deleteFirstLine()
        ->tap(expectVariantToBe('Last line.'));
});

it('removes the last line', function () {
    $variant = Vary::string(
        <<<END
        First line.
        Last line.
        END
    );

    $variant->deleteLastLine()
        ->tap(expectVariantToBe('First line.'));
});

it('deletes lines using regular expressions', function () {
    $variant = Vary::string(<<<END
        One apple pie.
        One humble pie.
        One apple TV.
    END);

    $variant->deleteLineMatches('.*pie.*')
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

    $variant->deleteLineMatches('First.*')
        ->tap(expectVariantToBe("Second line.\nLast line."));

    $variant->deleteLineMatches('Second.*')
        ->tap(expectVariantToBe("First line.\nLast line."));

    $variant->deleteLineMatches('Last.*')
        ->tap(expectVariantToBe("First line.\nSecond line."));

    Vary::string('One line only.')
        ->deleteLineMatches('.*')
        ->tap(expectVariantToBe(''));

    Vary::string("First line.\nSecond line.\n")
        ->deleteLineMatches('Second.*')
        ->tap(expectVariantToBe("First line.\n"));

    Vary::string("First line.\nSecond line.\n")
        ->deleteLineMatches('First.*')
        ->tap(expectVariantToBe("Second line.\n"));
});

it('sorts all lines by alphabetical order', function () {
    Vary::string(
        <<<END
        Some line.
        Another line.
        New line.
        END
    )
        ->sortLines()
        ->tap(expectVariantToBe(
            <<<END
            Another line.
            New line.
            Some line.
            END
        ));

    Vary::string("D\nA\nC\nB\n")
        ->sortLines()
        ->tap(expectVariantToBe("A\nB\nC\nD\n"));
});

it('sorts all lines by length', function () {
    Vary::string(
        <<<END
        Some line.
        Another line.
        New line.
        END
    )
        ->sortLinesByLength()
        ->tap(expectVariantToBe(
            <<<END
            New line.
            Some line.
            Another line.
            END
        ));

    Vary::string("DDDD\nA\nCCC\nBB\n")
        ->sortLines()
        ->tap(expectVariantToBe("A\nBB\nCCC\nDDDD\n"));
});

it('sorts all lines by a given custom order', function () {
    Vary::string(
        <<<END
        A. Some line. [3]
        B. Some line. [1]
        C. Some line. [2]

        END
    )
        ->sortLines(fn (string $value) => substr($value, -3))
        ->tap(expectVariantToBe(
            <<<END
            B. Some line. [1]
            C. Some line. [2]
            A. Some line. [3]

            END
        ));
});

it('returns the first and last lines of the content', function () {
    $variant = Vary::string(
        <<<END
        First line.
        Second line.
        Last line.
        END
    );

    expect($variant->getFirstLine())->toBe('First line.');
    expect($variant->getFirstLineWithEol())->toBe("First line.\n");
    expect($variant->getLastLine())->toBe('Last line.');
});

it('returns all lines in the content', function () {
    $variant = Vary::string(
        <<<END
        First line.
        Second line.
        Last line.
        END
    );

    expect($variant->getAllLines())->toBe([
        'First line.',
        'Second line.',
        'Last line.',
    ]);

    expect($variant->getAllLinesWithEol())->toBe([
        'First line.' . PHP_EOL,
        'Second line.' . PHP_EOL,
        'Last line.',
    ]);
});
