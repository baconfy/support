<?php

namespace Baconfy\Support\Concerns;

trait Runner
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
    return static::make()->handle(...$arguments);
  }
}