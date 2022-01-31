<?php

use Lorisleiva\Vary\Vary;

it('adds Laravel routes with their use statements', function () {
    // Given the following routes file.
    $content = <<<PHP
        <?php

        use App\Http\Controllers\ListAllArticlesController;
        use App\Http\Controllers\FetchArticleController;
        use Illuminate\Support\Facades\Auth;
        use Illuminate\Support\Facades\Route;

        Auth::routes();

        Route::get('/', function () {
            return view('welcome');
        });

        Route::get('articles', ListAllArticlesController::class)->name('articles.index');
        Route::get('articles/{article}', FetchArticleController::class)->name('articles.show');
        PHP;

    // When we add two routes and their imports.
    $variant = Vary::string($content)
        ->appendLine(
            <<<PHP
            Route::get('articles/new', CreateArticleController::class)->name('articles.create');
            Route::post('articles', StoreArticleController::class)->name('articles.store');
            PHP
        )
        ->appendLineInPattern(
            pattern: '/(?:use [^;]+;$\n)*(?:use [^;]+;$)/m',
            content: <<<PHP
                use App\Http\Controllers\CreateArticleController;
                use App\Http\Controllers\StoreArticleController;
                PHP,
            keepIndent: true,
        )
        ->sortPhpImports();

    // Then the routes file has been correctly updated.
    $expected = <<<PHP
        <?php

        use App\Http\Controllers\CreateArticleController;
        use App\Http\Controllers\FetchArticleController;
        use App\Http\Controllers\ListAllArticlesController;
        use App\Http\Controllers\StoreArticleController;
        use Illuminate\Support\Facades\Auth;
        use Illuminate\Support\Facades\Route;

        Auth::routes();

        Route::get('/', function () {
            return view('welcome');
        });

        Route::get('articles', ListAllArticlesController::class)->name('articles.index');
        Route::get('articles/{article}', FetchArticleController::class)->name('articles.show');
        Route::get('articles/new', CreateArticleController::class)->name('articles.create');
        Route::post('articles', StoreArticleController::class)->name('articles.store');
        PHP;

    expectString($variant)->toBe($expected);
});
