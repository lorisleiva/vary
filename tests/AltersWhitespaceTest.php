<?php

use Lorisleiva\Vary\Variant;
use Lorisleiva\Vary\Vary;

it('can update fragments before, after and between whitespaces', function () {
    $content = "  \t\n  \r\n Hello Moto \n  \t";
    $callback = fn (Variant $variant) => $variant->override('CHANGED');

    expect(Vary::string($content)->selectBeforeWhitespace($callback)->toString())
        ->toBe("CHANGED \n  \t");

    expect(Vary::string($content)->selectAfterWhitespace($callback)->toString())
        ->toBe("  \t\n  \r\n CHANGED");

    expect(Vary::string($content)->selectBetweenWhitespace($callback)->toString())
        ->toBe("  \t\n  \r\n CHANGED \n  \t");
});
