<?php

namespace Baconfy\Support;

use Baconfy\Support\Concerns\Transaction;
use Illuminate\Support\Facades\DB;

class Action
{
  /**
   * Action entry point
   */
  public static function run(mixed ...$arguments): mixed
  {
    if (in_array(Transaction::class, class_uses(static::class))) {
      return DB::transaction(function () use ($arguments) {
        return app(static::class)->handle(...$arguments);
      });
    }

    return app(static::class)->handle(...$arguments);
  }
}