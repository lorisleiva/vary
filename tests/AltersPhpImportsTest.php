<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Lorisleiva\Vary\Vary;

it('selects PHP import statements', function () {
    $useStatements = <<<PHP
        // Before

        use App\Http\Controllers\FetchArticleController;
        use Illuminate\Support\Facades\Route;
        use Illuminate\Support\Str;

        // After
        PHP;

    Vary::string($useStatements)
        ->phpImports()->select(expectVariantToBe(
            <<<PHP
            use App\Http\Controllers\FetchArticleController;
            use Illuminate\Support\Facades\Route;
            use Illuminate\Support\Str;
            PHP
        ));

    Vary::string($useStatements)
        ->phpImports()->selectWithEol(expectVariantToBe(
            <<<PHP

            use App\Http\Controllers\FetchArticleController;
            use Illuminate\Support\Facades\Route;
            use Illuminate\Support\Str;

            PHP
        ));
});

it('selects PHP import statements in multiple locations', function () {
    $useStatements = <<<PHP
        // A
        use App\Http\Controllers\FetchArticleController;
        use Illuminate\Support\Facades\Route;
        // B
        use Illuminate\Support\Str;
        // C
        PHP;

    Vary::string($useStatements)
        ->phpImports()->select(emptyVariant())
        ->tap(expectVariantToBe(
            <<<PHP
            // A

            // B

            // C
            PHP
        ));
});

it('does not select trait imports as PHP import statements', function () {
    $useStatements = <<<PHP
        use App\Http\Controllers\FetchArticleController;
        use Illuminate\Support\Facades\Route;

        class Foo {
            use SomeTrait;
        }
        PHP;

    Vary::string($useStatements)
        ->phpImports()->selectWithEol(emptyVariant())
        ->tap(expectVariantToBe(
            <<<PHP

            class Foo {
                use SomeTrait;
            }
            PHP
        ));
});

it('sorts PHP import statements', function () {
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

it('sorts PHP import statements by length', function () {
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

it('adds PHP imports', function () {
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

it('replaces PHP imports', function () {
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

it('deletes PHP imports', function () {
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
        ->tap(expectVariantToBe(
            <<<PHP

            PHP
        ));
});
