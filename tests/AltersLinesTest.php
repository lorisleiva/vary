<?php

use Lorisleiva\Vary\Vary;

test('addAfterExactLine', function () {
    $variant = Vary::string(
        <<<END
            One apple pie.
            One humble pie.
        END
    );

    $variant->addAfterExactLine('    One apple pie.', 'NEW_LINE', true)
        ->tap(expectVariantToBe(
            <<<END
                One apple pie.
                NEW_LINE
                One humble pie.
            END
        ));

    $variant->addAfterExactLine('One apple pie.', 'NEW_LINE')
        ->tap(expectVariantToBe($variant->toString()));
    $variant->addAfterExactLine('*apple*', 'NEW_LINE')
        ->tap(expectVariantToBe($variant->toString()));
});

test('addAfterLine', function () {
    $variant = Vary::string(
        <<<END
            One apple pie.
                One humble pie.
        END
    );

    $variant->addAfterLine('One apple*', 'NEW_LINE')
        ->tap(expectVariantToBe(
            <<<END
                One apple pie.
            NEW_LINE
                    One humble pie.
            END
        ));

    $variant->addAfterLine('One apple*', 'NEW_LINE', true)
        ->tap(expectVariantToBe(
            <<<END
                One apple pie.
                NEW_LINE
                    One humble pie.
            END
        ));
});

test('addAfterLineMatches', function () {
    Vary::string(
        <<<END
            One apple pie.
            One humble pie.
        END
    )
        ->addAfterLineMatches('.*pie.*', 'NEW LINE',  true)
        ->tap(expectVariantToBe(
            <<<END
                One apple pie.
                NEW LINE
                One humble pie.
                NEW LINE
            END
        ));
});

test('addBeforeExactLine', function () {
    $variant = Vary::string(
        <<<END
            One apple pie.
            One humble pie.
        END
    );

    $variant->addBeforeExactLine('    One humble pie.', 'NEW_LINE', true)
        ->tap(expectVariantToBe(
            <<<END
                One apple pie.
                NEW_LINE
                One humble pie.
            END
        ));

    $variant->addBeforeExactLine('One humble pie.', 'NEW_LINE')
        ->tap(expectVariantToBe($variant->toString()));
    $variant->addBeforeExactLine('*humble*', 'NEW_LINE')
        ->tap(expectVariantToBe($variant->toString()));
});

test('addBeforeLine', function () {
    $variant = Vary::string(
        <<<END
            One apple pie.
                One humble pie.
        END
    );

    $variant->addBeforeLine('One humble*', 'NEW_LINE')
        ->tap(expectVariantToBe(
            <<<END
                One apple pie.
            NEW_LINE
                    One humble pie.
            END
        ));

    $variant->addBeforeLine('One humble*', 'NEW_LINE', true)
        ->tap(expectVariantToBe(
            <<<END
                One apple pie.
                    NEW_LINE
                    One humble pie.
            END
        ));
});

test('addBeforeLineMatches', function () {
    Vary::string(
        <<<END
            One apple pie.
            One humble pie.
        END
    )
        ->addBeforeLineMatches('.*pie.*', 'NEW LINE',  true)
        ->tap(expectVariantToBe(
            <<<END
                NEW LINE
                One apple pie.
                NEW LINE
                One humble pie.
            END
        ));
});

test('appendLine', function () {
    $variant = Vary::string(
        <<<END
            One apple pie.
            One humble pie.
        END
    );

    $variant->appendLine('NEW_LINE')
        ->tap(expectVariantToBe(
            <<<END
                One apple pie.
                One humble pie.
            NEW_LINE
            END
        ));

    $variant->appendLine('NEW_LINE', true)
        ->tap(expectVariantToBe(
            <<<END
                One apple pie.
                One humble pie.
                NEW_LINE
            END
        ));

    Vary::string('')->appendLine('NEW_LINE', keepIndent: true)
        ->tap(expectVariantToBe('NEW_LINE'));
});

test('cleanEmptyLines', function () {
    Vary::string("A\n   \n \t \nB\nC\n")
        ->cleanEmptyLines()
        ->tap(expectVariantToBe("A\n\nB\nC\n"));

    Vary::string("A\n   \n \t \nB\n\n\nC\n")
        ->cleanEmptyLines(ignoreWhitespace: false)
        ->tap(expectVariantToBe("A\n   \n \t \nB\n\nC\n"));

    Vary::string("A\n\n\n\n")
        ->cleanEmptyLines()
        ->tap(expectVariantToBe("A\n\n"));

    Vary::string("\n\n\n\nA")
        ->cleanEmptyLines()
        ->tap(expectVariantToBe("\nA"));

    Vary::string("A\n\n\n\nB")
        ->cleanEmptyLines(2)
        ->tap(expectVariantToBe("A\n\nB"));

    Vary::string("A\n\n\n\nB")
        ->cleanEmptyLines(3)
        ->tap(expectVariantToBe("A\n\n\n\nB"));

    Vary::string("A\n\n\n\nB\n\n\n\nC")
        ->cleanEmptyLines(limit: 1)
        ->tap(expectVariantToBe("A\n\nB\n\n\n\nC"));
});

test('deleteFirstLine', function () {
    Vary::string("First line.\nLast line.")
        ->deleteFirstLine()
        ->tap(expectVariantToBe('Last line.'));
});

test('deleteLastLine', function () {
    Vary::string("First line.\nLast line.")
        ->deleteLastLine()
        ->tap(expectVariantToBe('First line.'));
});

test('deleteExactLine', function () {
    $variant = Vary::string(
        <<<END
            First line.
            Second line.
            Last line.
        END
    );

    $variant->deleteExactLine('    First line.')
        ->tap(expectVariantToBe("    Second line.\n    Last line."));
    $variant->deleteExactLine('First*')
        ->tap(expectVariantToBe($variant->toString()));
});

test('deleteExactLines', function () {
    $variant = Vary::string(
        <<<END
            First line.
            Second line.
            Last line.
        END
    );

    $variant->deleteExactLines(['    First line.', '    Second line.'])
        ->tap(expectVariantToBe('    Last line.'));
    $variant->deleteExactLines(['First*', 'Second*'])
        ->tap(expectVariantToBe($variant->toString()));
});

test('deleteLine', function () {
    $variant = Vary::string(<<<END
        One apple pie.
        One humble pie.
        One apple TV.
    END);

    $variant->deleteLine('One apple*')->tap(expectVariantToBe('    One humble pie.'));
    $variant->deleteLine('One apple*', limit: 1)->tap(expectVariantToBe("    One humble pie.\n    One apple TV."));
    $variant->deleteLine('    One apple*', ignoreWhitespace: false)->tap(expectVariantToBe('    One humble pie.'));

    $variant = Vary::string(
        <<<END
        First line.
        Second line.
        Last line.
        END
    );

    $variant->deleteLine('First line.')->tap(expectVariantToBe("Second line.\nLast line."));
    $variant->deleteLine('Second line.')->tap(expectVariantToBe("First line.\nLast line."));
    $variant->deleteLine('Last line.')->tap(expectVariantToBe("First line.\nSecond line."));

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

test('deleteLineMatches', function () {
    $variant = Vary::string(
        <<<END
        First line.
        Second line.
        Last line.
        END
    );

    $variant->deleteLineMatches('.*t line\.')->tap(expectVariantToBe('Second line.'));
    $variant->deleteLineMatches('First.*')->tap(expectVariantToBe("Second line.\nLast line."));
    $variant->deleteLineMatches('Second.*')->tap(expectVariantToBe("First line.\nLast line."));
    $variant->deleteLineMatches('Last.*')->tap(expectVariantToBe("First line.\nSecond line."));

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

test('deleteLines', function () {
    $variant = Vary::string(
        <<<END
        First line.
        Second line.
        Last line.
        END
    );

    $variant->deleteLines(['First*', 'Second*'])
        ->tap(expectVariantToBe('Last line.'));
});

test('getAllLines', function () {
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
});

test('getAllLinesWithEol', function () {
    $variant = Vary::string(
        <<<END
        First line.
        Second line.
        Last line.
        END
    );

    expect($variant->getAllLinesWithEol())->toBe([
        'First line.' . PHP_EOL,
        'Second line.' . PHP_EOL,
        'Last line.',
    ]);
});

test('getFirstLine', function () {
    $variant = Vary::string(
        <<<END
        First line.
        Second line.
        Last line.
        END
    );

    expect($variant->getFirstLine())->toBe('First line.');
});

test('getFirstLineWithEol', function () {
    $variant = Vary::string(
        <<<END
        First line.
        Second line.
        Last line.
        END
    );

    expect($variant->getFirstLineWithEol())->toBe("First line.\n");
});

test('getLastLine', function () {
    $variant = Vary::string(
        <<<END
        First line.
        Second line.
        Last line.
        END
    );

    expect($variant->getLastLine())->toBe('Last line.');
});

test('getLastLineWithEol', function () {
    $variant = Vary::string(
        <<<END
        First line.
        Second line.
        Last line.
        END
    );

    expect($variant->getLastLineWithEol())->toBe("\nLast line.");
});

test('prependLine', function () {
    $variant = Vary::string(
        <<<END
            One apple pie.
            One humble pie.
        END
    );

    $variant->prependLine('NEW_LINE')
        ->tap(expectVariantToBe(
            <<<END
            NEW_LINE
                One apple pie.
                One humble pie.
            END
        ));

    $variant->prependLine('NEW_LINE', true)
        ->tap(expectVariantToBe(
            <<<END
                NEW_LINE
                One apple pie.
                One humble pie.
            END
        ));

    Vary::string('')->prependLine('NEW_LINE', keepIndent: true)
        ->tap(expectVariantToBe('NEW_LINE'));
});

test('selectAllLines', function () {
    Vary::string("One apple pie.\nOne humble pie.")
        ->selectAllLines(overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe("CHANGED\nCHANGED"));

    Vary::string('')
        ->selectAllLines(overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe("CHANGED"));
});

test('selectAllLinesWithEol', function () {
    Vary::string("One apple pie.\nOne humble pie.")
        ->selectAllLinesWithEol(overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe("CHANGEDCHANGED"));

    Vary::string('')
        ->selectAllLinesWithEol(overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe("CHANGED"));
});

test('selectExactLine', function () {
    $variant = Vary::string(<<<END
        One apple pie.
        One humble pie.
        One apple TV.
    END);

    $variant->selectExactLine('One apple pie.', expectVariantNotToBeCalled());
    $variant->selectExactLine('    One apple pie.', expectVariantToBe("    One apple pie."));
    $variant->selectExactLine('*apple*', expectVariantNotToBeCalled());
});

test('selectExactLineWithEol', function () {
    $variant = Vary::string(<<<END
        One apple pie.
        One humble pie.
        One apple TV.
    END);

    $variant->selectExactLineWithEol('One apple pie.', expectVariantNotToBeCalled());
    $variant->selectExactLineWithEol('    One apple pie.', expectVariantToBe("    One apple pie.\n"));
    $variant->selectExactLineWithEol('*apple*', expectVariantNotToBeCalled());
});

test('selectFirstLine', function () {
    Vary::string("One apple pie.\nOne humble pie.")
        ->selectFirstLine(expectVariantToBe("One apple pie."));

    Vary::string('')
        ->selectFirstLine(expectVariantToBe(''));
});

test('selectFirstLineWithEol', function () {
    Vary::string("One apple pie.\nOne humble pie.")
        ->selectFirstLineWithEol(expectVariantToBe("One apple pie.\n"));

    Vary::string('')
        ->selectFirstLineWithEol(expectVariantToBe(''));
});

test('selectLastLine', function () {
    Vary::string("One apple pie.\nOne humble pie.")
        ->selectLastLine(expectVariantToBe("One humble pie."));

    Vary::string('')
        ->selectLastLine(expectVariantToBe(''));
});

test('selectLastLineWithEol', function () {
    Vary::string("One apple pie.\nOne humble pie.")
        ->selectLastLineWithEol(expectVariantToBe("\nOne humble pie."));

    Vary::string('')
        ->selectLastLineWithEol(expectVariantToBe(''));
});

test('selectLine', function () {
    $variant = Vary::string(<<<END
        One apple pie.
        One humble pie.
        One apple TV.
    END);

    $variant->selectLine('One apple pie.', expectVariantToBe("    One apple pie."));
    $variant->selectLine('One apple pie.', expectVariantNotToBeCalled(), ignoreWhitespace: false);
    $variant->selectLine('    One apple pie.', expectVariantToBe("    One apple pie."), ignoreWhitespace: false);
    $variant->selectLine('*apple pie*', expectVariantToBe("    One apple pie."));
    $variant->selectLine('One apple pie*', expectVariantNotToBeCalled(), ignoreWhitespace: false);
    $variant->selectLine('*apple*', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe(
            <<<END
            CHANGED
                One humble pie.
            CHANGED
            END
        ));
});

test('selectLineMatches', function () {
    $variant = Vary::string(
        <<<END
        One apple pie.
        One humble pie.
        One apple TV.
        END
    );

    $variant->selectLineMatches('.*TV\.', expectVariantToBe('One apple TV.'));
    $variant->selectLineMatches('TV', expectVariantNotToBeCalled());
    $variant->selectLineMatches('.*humble.*', expectVariantToBe('One humble pie.'));
    $variant->selectLineMatches('.*apple.*', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe("CHANGED\nOne humble pie.\nCHANGED"));
});

test('selectLineMatchesWithEol', function () {
    $variant = Vary::string(
        <<<END
        One apple pie.
        One humble pie.
        One apple TV.
        END
    );

    $variant->selectLineMatchesWithEol('.*TV\.', expectVariantToBe("\nOne apple TV."));
    $variant->selectLineMatchesWithEol('TV', expectVariantNotToBeCalled());
    $variant->selectLineMatchesWithEol('.*humble.*', expectVariantToBe("One humble pie.\n"));
    $variant->selectLineMatchesWithEol('.*apple.*', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe("CHANGEDOne humble pie.CHANGED"));
});

test('selectLineWithEol', function () {
    $variant = Vary::string(<<<END
        One apple pie.
        One humble pie.
        One apple TV.
    END);

    $variant->selectLineWithEol('One apple pie.', expectVariantToBe("    One apple pie.\n"));
    $variant->selectLineWithEol('*apple pie*', expectVariantToBe("    One apple pie.\n"));
    $variant->selectLineWithEol('*TV*', expectVariantToBe("\n    One apple TV."));
    $variant->selectLineWithEol('*apple*', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe("CHANGED    One humble pie.CHANGED"));
    $variant->selectLineWithEol('*pie*', overrideVariantTo('CHANGED'))
        ->tap(expectVariantToBe("CHANGEDCHANGED    One apple TV."));
});

test('sortLines', function () {
    Vary::string(
        <<<END
        Some line.
        Another line.
        New line.
        END
    )->sortLines()->tap(expectVariantToBe(
        <<<END
        Another line.
        New line.
        Some line.
        END
    ));

    Vary::string("D\nA\nC\nB\n")
        ->sortLines()
        ->tap(expectVariantToBe("A\nB\nC\nD\n"));

    Vary::string("\n\nB\n\nA\n\n")
        ->sortLines()
        ->tap(expectVariantToBe("\n\n\n\nA\nB\n"));

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

test('sortLinesByLength', function () {
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
