<?php

namespace Lorisleiva\Vary;

use Illuminate\Filesystem\Filesystem;
use JetBrains\PhpStorm\Pure;

class Vary
{
    #[Pure] public static function file(string $path): Variant
    {
        $value = file_exists($path) ? file_get_contents($path) : '';

        return new Variant($value, $path);
    }

    #[Pure] public static function string(string $value): Variant
    {
        return new Variant($value, null);
    }

    #[Pure] public static function filesystem(): Filesystem
    {
        return new Filesystem();
    }
}
