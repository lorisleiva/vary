<?php

use Illuminate\Filesystem\Filesystem;
use Lorisleiva\Vary\Variant;

function expectString($any)
{
    return expect((string) $any);
}

function expectVariantToBe(string $expected): Closure
{
    return function (Variant $variant) use ($expected) {
        expect((string) $variant)->toBe($expected);

        return $variant;
    };
}

function stubs(string $path): string
{
    return __DIR__ . "/stubs/$path";
}

function tmp(string $path): string
{
    return __DIR__ . "/tmp/$path";
}

function cleanTmp(): void
{
    (new Filesystem)->deleteDirectory(__DIR__ . "/tmp", preserve: true);
    (new Filesystem)->put(__DIR__ . "/tmp/.gitkeep", '');
}
