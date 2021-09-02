<?php

use Lorisleiva\Vary\Vary;

it('returns the path of the file initially loaded', function () {
    expect(Vary::file(stubs('routes.php'))->getPath())
        ->toBe(stubs('routes.php'));
});

it('returns the entire content as a string', function () {
    expect(Vary::string('Some text')->toString())
        ->toBe('Some text');
});
