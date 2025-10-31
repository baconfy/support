<?php

use Baconfy\Support\Tests\Fakes\FakeStatus;

it('returns only the names of cases with names()', function () {
    expect(FakeStatus::names())->toBe(['ACTIVE', 'PENDING', 'DISABLED']);
});

it('returns only the values of cases with values()', function () {
    expect(FakeStatus::values())->toBe(['active', 'pending', 'disabled']);
});

it('returns an array combining values as key and names as value with array()', function () {
    expect(FakeStatus::array())->toBe([
        'active' => 'ACTIVE',
        'pending' => 'PENDING',
        'disabled' => 'DISABLED',
    ]);
});

it('maintains correct correspondence between names and values', function () {
    $names = FakeStatus::names();
    $values = FakeStatus::values();

    expect(count($names))->toBe(count($values))
        ->and(array_keys(FakeStatus::array()))->toBe($values)
        ->and(array_values(FakeStatus::array()))->toBe($names);
});
