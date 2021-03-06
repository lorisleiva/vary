<?php

namespace Lorisleiva\Vary;

use Illuminate\Filesystem\Filesystem;

class Vary
{
    public static function file(string $path): Variant
    {
        $value = file_exists($path) ? file_get_contents($path) : '';

        return new Variant($value, $path);
    }

    public static function string(string $value): Variant
    {
        return new Variant($value, null);
    }

    public static function filesystem(): Filesystem
    {
        return new Filesystem();
    }
}
