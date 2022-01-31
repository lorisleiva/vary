<?php

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
        ->selectPhpImports(expectVariantToBe(
            <<<PHP
            use App\Http\Controllers\FetchArticleController;
            use Illuminate\Support\Facades\Route;
            use Illuminate\Support\Str;
            PHP
        ));

    Vary::string($useStatements)
        ->selectPhpImportsWithEol(expectVariantToBe(
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
        ->selectPhpImportsWithEol(emptyVariant())
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
        ->selectPhpImportsWithEol(emptyVariant())
        ->tap(expectVariantToBe(
            <<<PHP

            class Foo {
                use SomeTrait;
            }
            PHP
        ));
});
