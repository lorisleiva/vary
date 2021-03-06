<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Lorisleiva\Vary\Vary;

it('matches PHP import statements', function () {
    $useStatements = <<<PHP
        // Before

        use App\Http\Controllers\FetchArticleController;
        use Illuminate\Support\Facades\Route;
        use Illuminate\Support\Str;

        // After
        PHP;

    Vary::string($useStatements)
        ->phpImports()->match()->tap(expectVariantToBe(
            <<<PHP
            use App\Http\Controllers\FetchArticleController;
            use Illuminate\Support\Facades\Route;
            use Illuminate\Support\Str;
            PHP
        ));
});

it('matches PHP import statements in multiple locations', function () {
    $useStatements = <<<PHP
        // A
        use App\Http\Controllers\FetchArticleController;
        use Illuminate\Support\Facades\Route;
        // B
        use Illuminate\Support\Str;
        // C
        PHP;

    $matches = Vary::string($useStatements)->phpImports()->matchAll();
    expect($matches)->toBe([
        "use App\Http\Controllers\FetchArticleController;\nuse Illuminate\Support\Facades\Route;",
        "use Illuminate\Support\Str;",
    ]);
});

it('does not match trait imports as PHP import statements', function () {
    $useStatements = <<<PHP
        use App\Http\Controllers\FetchArticleController;
        use Illuminate\Support\Facades\Route;

        class Foo {
            use SomeTrait;
        }
        PHP;

    $matches = Vary::string($useStatements)->phpImports()->matchAll();
    expect($matches)->toBe([
        "use App\Http\Controllers\FetchArticleController;\nuse Illuminate\Support\Facades\Route;",
    ]);
});

test('add', function () {
    $useStatements = <<<PHP
        use Illuminate\Support\Str;
        use App\Http\Controllers\FetchArticleController;

        use App\Models\Article;
        use App\Models\User;
        PHP;

    Vary::string($useStatements)
        ->phpImports()->add(Arr::class, Str::class)
        ->tap(expectVariantToBe(
            <<<PHP
            use Illuminate\Support\Str;
            use App\Http\Controllers\FetchArticleController;
            use Illuminate\Support\Arr;
            use Illuminate\Support\Str;

            use App\Models\Article;
            use App\Models\User;
            PHP
        ));

    Vary::string("use Illuminate\Support\Str;")
        ->phpImports()->add(Arr::class)
        ->tap(expectVariantToBe(
            <<<PHP
            use Illuminate\Support\Str;
            use Illuminate\Support\Arr;
            PHP
        ));
});

test('delete', function () {
    $useStatements = <<<PHP
        use Illuminate\Support\Str;
        use App\Http\Controllers\FetchArticleController;

        use App\Models\Article;
        use Illuminate\Support\Str;
        PHP;

    Vary::string($useStatements)
        ->phpImports()->delete(Str::class, 'App\Models\Article')
        ->tap(expectVariantToBe(
            <<<PHP
            use App\Http\Controllers\FetchArticleController;

            PHP
        ));

    Vary::string($useStatements)
        ->phpImports()->delete()
        ->tap(expectVariantToBe(''));
});

test('replace', function () {
    $useStatements = <<<PHP
        use Illuminate\Support\Str;
        use App\Http\Controllers\FetchArticleController;

        use App\Models\Article;
        use Illuminate\Support\Str;
        PHP;

    Vary::string($useStatements)
        ->phpImports()->replace(Str::class, Arr::class)
        ->tap(expectVariantToBe(
            <<<PHP
            use Illuminate\Support\Arr;
            use App\Http\Controllers\FetchArticleController;

            use App\Models\Article;
            use Illuminate\Support\Arr;
            PHP
        ));
});

test('replaceAll', function () {
    $useStatements = <<<PHP
        use Illuminate\Support\Str;
        use App\Http\Controllers\FetchArticleController;

        use App\Models\Article;
        use Illuminate\Support\Str;
        PHP;

    Vary::string($useStatements)
        ->phpImports()->replaceAll([
            Str::class => Arr::class,
            'App\Models\Article' => 'App\Models\Post',
        ])
        ->tap(expectVariantToBe(
            <<<PHP
            use Illuminate\Support\Arr;
            use App\Http\Controllers\FetchArticleController;

            use App\Models\Post;
            use Illuminate\Support\Arr;
            PHP
        ));
});

test('sort', function () {
    $useStatements = <<<PHP
        use Illuminate\Support\Str;
        use App\Http\Controllers\FetchArticleController;
        use Illuminate\Support\Facades\Route;

        use App\Models\User;
        use App\Models\Article;
        PHP;

    Vary::string($useStatements)
        ->phpImports()->sort()
        ->tap(expectVariantToBe(
            <<<PHP
            use App\Http\Controllers\FetchArticleController;
            use Illuminate\Support\Facades\Route;
            use Illuminate\Support\Str;

            use App\Models\Article;
            use App\Models\User;
            PHP
        ));
});

test('sortByLength', function () {
    $useStatements = <<<PHP
        use Illuminate\Support\Str;
        use App\Http\Controllers\FetchArticleController;
        use Illuminate\Support\Facades\Route;

        use App\Models\Article;
        use App\Models\User;
        PHP;

    Vary::string($useStatements)
        ->phpImports()->sortByLength()
        ->tap(expectVariantToBe(
            <<<PHP
            use Illuminate\Support\Str;
            use Illuminate\Support\Facades\Route;
            use App\Http\Controllers\FetchArticleController;

            use App\Models\User;
            use App\Models\Article;
            PHP
        ));
});
