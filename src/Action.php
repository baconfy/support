<?php

namespace Baconfy\Support;

use Baconfy\Support\Concerns\Transaction;
use Illuminate\Support\Facades\DB;

class Action
{
  /**
   * Make a static class
   */
  public static function make(): static
  {
    return app(static::class);
  }

  /**
   * Action entry point
   */
  public static function run(mixed ...$arguments): mixed
  {
    if (in_array(Transaction::class, class_uses(static::class))) {
      return DB::transaction(function () use ($arguments) {
        return static::make()->handle(...$arguments);
      });
    }

    return static::make()->handle(...$arguments);
  }
}