<?php

use Baconfy\Support\Tests\Fakes\FakeEnum;

it('returns only the names of cases with names()', function () {
    expect(FakeEnum::names())->toBe(['ACTIVE', 'PENDING', 'DISABLED']);
});

it('returns only the values of cases with values()', function () {
    expect(FakeEnum::values())->toBe(['active', 'pending', 'disabled']);
});

it('returns an array combining values as key and names as value with array()', function () {
    expect(FakeEnum::array())->toBe([
        'active' => 'ACTIVE',
        'pending' => 'PENDING',
        'disabled' => 'DISABLED',
    ]);
});

it('maintains correct correspondence between names and values', function () {
    $names = FakeEnum::names();
    $values = FakeEnum::values();

    expect(count($names))->toBe(count($values))
        ->and(array_keys(FakeEnum::array()))->toBe($values)
        ->and(array_values(FakeEnum::array()))->toBe($names);
});
