<?php

use Lorisleiva\Vary\Variant;
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

    // When
    $variant = Vary::string($content)
        // ->appendLine(
        //     <<<PHP
        //     Route::get('articles/new', CreateArticleController::class)->name('articles.create');
        //     Route::post('articles', StoreArticleController::class)->name('articles.store');
        //     PHP
        // )
        ->selectPattern('/(?:use [^;]+;$\n?)+/m', function (Variant $variant) {
            return $variant->appendLine(<<<PHP
                use App\Http\Controllers\CreateArticleController;
                use App\Http\Controllers\StoreArticleController;
                PHP);
        })
        // ->addAfterPattern(
        //     pattern: '/(?:use [^;]+;$\n?)+/m',
        //     content: <<<PHP
        //         use App\Http\Controllers\CreateArticleController;
        //         use App\Http\Controllers\StoreArticleController;
        //
        //         PHP,
        // )
    ;

    // Then
    $expected = <<<PHP
        <?php

        use App\Http\Controllers\ListAllArticlesController;
        use App\Http\Controllers\FetchArticleController;
        use Illuminate\Support\Facades\Auth;
        use Illuminate\Support\Facades\Route;
        use App\Http\Controllers\CreateArticleController;
        use App\Http\Controllers\StoreArticleController;

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
})->skip();