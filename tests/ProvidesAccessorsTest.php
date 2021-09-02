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

it('returns the text matching a given pattern', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    expect($variant->match('/One .*? pie/'))->toBe('One apple pie');
    expect($variant->match('/One (.*?) pie/'))->toBe('apple');
});

it('returns an array of all texts matching a given pattern', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    expect($variant->matchAll('/One .*? pie/'))->toBe(['One apple pie', 'One humble pie']);
    expect($variant->matchAll('/One (.*?) pie/'))->toBe(['apple', 'humble']);
});
