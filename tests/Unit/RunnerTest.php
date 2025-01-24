<?php

use Baconfy\Support\Tests\Fake\ConcatenateTwoStrings;
use Baconfy\Support\Tests\Fake\TransactionalAction;
use Baconfy\Support\Concerns\Runner;
use Illuminate\Support\Facades\DB;

it('use a runner trait', function () {
  expect(ConcatenateTwoStrings::class)->toUseTrait(Runner::class);
});

it('can make an runner class', function () {
  $concatenate = ConcatenateTwoStrings::make();

  expect($concatenate)->toBeInstanceOf(ConcatenateTwoStrings::class);
});

it('can extends an Action class', function () {
  $concatenate = ConcatenateTwoStrings::run('Awesome', 'Test');

  expect($concatenate)->toBe("Awesome Test");
});

it('ensures DB::transaction is called', function () {
  DB::shouldReceive('transaction')->once()->andReturnUsing(function ($callback) {
    return $callback();
  });

  TransactionalAction::run();
});