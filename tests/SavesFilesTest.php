<?php

use Lorisleiva\Vary\Vary;

afterEach(function () {
    // Ensure the temporary folder is clean between tests.
    cleanTmp();
});

it('loads and saves files at the same path', function () {
    // Given a stub file.
    $path = stubs('routes.php');

    // When we create a variant from it.
    $variant = Vary::file($path);

    // Then we kept its content and its destination.
    expect($variant->toString())->toBe(file_get_contents($path));
    expect($variant->getPath())->toBe($path);
});

it('can save files to different paths', function () {
    // Given a stub file.
    $path = stubs('routes.php');
    $content = file_get_contents($path);

    // When we save its variant using a different path
    // via the save or the (aliased) saveAs method.
    Vary::file($path)
        ->save(tmp('routesA.php'))
        ->saveAs(tmp('routesB.php'));

    // Then the files were created at the given paths.
    expect(file_exists(tmp('routesA.php')))->toBeTrue();
    expect(file_exists(tmp('routesB.php')))->toBeTrue();

    // And they contain the original content.
    expect(file_get_contents(tmp('routesA.php')))->toBe($content);
    expect(file_get_contents(tmp('routesB.php')))->toBe($content);
});
