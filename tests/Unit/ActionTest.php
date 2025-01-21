<?php

use Baconfy\Support\Tests\Fake\ConcatenateTwoStrings;
use Baconfy\Support\Tests\Fake\TransactionalAction;
use Illuminate\Support\Facades\DB;

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