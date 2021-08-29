<?php

use Illuminate\Filesystem\Filesystem;

function stubs(string $path): string
{
    return __DIR__ . "/stubs/$path";
}

function tmp(string $path): string
{
    return __DIR__ . "/tmp/$path";
}

function cleanTmp(): string
{
    (new Filesystem)->deleteDirectory(__DIR__ . "/tmp", preserve: true);
    (new Filesystem)->put(__DIR__ . "/tmp/.gitkeep", '');
}
