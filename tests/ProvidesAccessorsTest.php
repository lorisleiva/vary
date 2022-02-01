<?php

use Lorisleiva\Vary\Vary;

test ('contains', function () {
    expect(Vary::string('Some Text')->contains('Some'))->toBeTrue();
    expect(Vary::string('Some Text')->contains('Foo'))->toBeFalse();
    expect(Vary::string('Some Text')->contains(['Some', 'Foo']))->toBeTrue();
});

test ('containsAll', function () {
    expect(Vary::string('Some Text')->containsAll(['Some', 'Text']))->toBeTrue();
    expect(Vary::string('Some Text')->containsAll(['Some', 'Foo']))->toBeFalse();
});

test ('endsWith', function () {
    expect(Vary::string('Some Text')->endsWith('Text'))->toBeTrue();
    expect(Vary::string('Some Text')->endsWith('Some'))->toBeFalse();
});

test ('exactly', function () {
    expect(Vary::string('Some Text')->exactly('Some Text'))->toBeTrue();
    expect(Vary::string('Some Text')->exactly('Some Text.'))->toBeFalse();
});

test ('explode', function () {
    expect(Vary::string('Some Text')->explode(' '))->toBe(['Some', 'Text']);
    expect(Vary::string('Some Text')->explode('e'))->toBe(['Som', ' T', 'xt']);
});

test ('getPath', function () {
    expect(Vary::file(stubs('routes.php'))->getPath())->toBe(stubs('routes.php'));
    expect(Vary::string('Some Text')->getPath())->toBeNull();
});

test ('is', function () {
    expect(Vary::string('Some Text')->is('Some Text'))->toBeTrue();
    expect(Vary::string('Some Text')->is('Some Text.'))->toBeFalse();
    expect(Vary::string('Some Text')->is('Some*'))->toBeTrue();
    expect(Vary::string('Some Text')->is('*Text'))->toBeTrue();
    expect(Vary::string('Some Text')->is('*Foo*'))->toBeFalse();
});

test ('isAscii', function () {
    expect(Vary::string('Some Text')->isAscii())->toBeTrue();
    expect(Vary::string('👋')->isAscii())->toBeFalse();
});

test ('isEmpty', function () {
    expect(Vary::string('')->isEmpty())->toBeTrue();
    expect(Vary::string('Some Text')->isEmpty())->toBeFalse();
});

test ('isNotEmpty', function () {
    expect(Vary::string('')->isNotEmpty())->toBeFalse();
    expect(Vary::string('Some Text')->isNotEmpty())->toBeTrue();
});

test ('isUuid', function () {
    expect(Vary::string('Some Text')->isUuid())->toBeFalse();
    expect(Vary::string('bd1a9004-834f-11ec-a8a3-0242ac120002')->isUuid())->toBeTrue(); // v1
    expect(Vary::string('9f86ed39-dbc2-4167-83b5-8638be609c3c')->isUuid())->toBeTrue(); // v4
});

test ('length', function () {
    expect(Vary::string('')->length())->toBe(0);
    expect(Vary::string('Some Text')->length())->toBe(9);
});

test ('matchAll', function () {
    $variant = Vary::string('One apple pie. One humble pie. One apple TV.');

    expect($variant->matchAll('/One .*? pie/'))->toBe(['One apple pie', 'One humble pie']);
    expect($variant->matchAll('/One (.*?) pie/'))->toBe(['apple', 'humble']);
});

test ('scan', function () {
    expect(Vary::string('Some Text')->scan('Some %s'))->toBe(['Text']);
    expect(Vary::string('Some Text')->scan('%s %s'))->toBe(['Some', 'Text']);
    expect(Vary::string('Some Number 42')->scan('%s Number %d'))->toBe(['Some', 42]);
});

test ('split', function () {
    expect(Vary::string('Some Text')->split('/\s/'))->toBe(['Some', 'Text']);
    expect(Vary::string('Some---=Text=--=Foo')->split('/[-=]+/'))->toBe(['Some', 'Text', 'Foo']);
});

test ('startsWith', function () {
    expect(Vary::string('Some Text')->startsWith('Some'))->toBeTrue();
    expect(Vary::string('Some Text')->startsWith('Text'))->toBeFalse();
});

test ('substrCount', function () {
    expect(Vary::string('Text Some Text')->substrCount('Text'))->toBe(2);
    expect(Vary::string('Text Some Text')->substrCount('Text', 0, 4))->toBe(1);
    expect(Vary::string('Text Some Text')->substrCount('Text', 5))->toBe(1);
    expect(Vary::string('Text Some Text')->substrCount('Text', 5, 4))->toBe(0);
});

test ('test', function () {
    expect(Vary::string('Some Text')->test('/^Some\s\w{4}$/'))->toBeTrue();
    expect(Vary::string('Some Text')->test('/T(ex)t/'))->toBeTrue();
    expect(Vary::string('Some Text')->test('/^Text.*$/'))->toBeFalse();
});

test ('toString', function () {
    expect(Vary::string('Some Text')->toString())->toBe('Some Text');
    expect(Vary::string('')->toString())->toBe('');
});

test ('ucsplit', function () {
    expect(Vary::string('Some Text')->ucsplit())->toBe(['Some ', 'Text']);
    expect(Vary::string('SomeMagicText')->ucsplit())->toBe(['Some', 'Magic', 'Text']);
});

test ('wordCount', function () {
    expect(Vary::string('Some Text')->wordCount())->toBe(2);
    expect(Vary::string('Some longer sentence with six words')->wordCount())->toBe(6);
    expect(Vary::string('Numbers 42 do not counts 123')->wordCount())->toBe(4);
});
