<?php

use Lorisleiva\Vary\Vary;

it('replaces all instances of a mustache variable with the given value', function () {
    Vary::string('One apple {{ patisserie }}. One humble {{ patisserie }}. One apple TV.')
        ->replaceMustache('patisserie', 'pie')
        ->tap(expectVariantToBe('One apple pie. One humble pie. One apple TV.'));
});

it('replaces multiple mustache variables within a given array of data', function () {
    Vary::string('{{ number }} apple {{ patisserie }}. {{ number }} humble {{ patisserie }}. {{ number }} apple TV.')
        ->replaceAllMustaches(['number' => 'Two', 'patisserie' => 'pie'])
        ->tap(expectVariantToBe('Two apple pie. Two humble pie. Two apple TV.'));
});

it('ignores any whitespace within the mustache variables', function () {
    expect(Vary::string('{{number}}')->replaceMustache('number', 'One')->toString())->toBe('One');
    expect(Vary::string('{{ number}}')->replaceMustache('number', 'One')->toString())->toBe('One');
    expect(Vary::string('{{number }}')->replaceMustache('number', 'One')->toString())->toBe('One');
    expect(Vary::string('{{ number }}')->replaceMustache('number', 'One')->toString())->toBe('One');
    expect(Vary::string('{{      number      }}')->replaceMustache('number', 'One')->toString())->toBe('One');
    expect(Vary::string("{{ number \t\n\r}}")->replaceMustache('number', 'One')->toString())->toBe('One');
});
