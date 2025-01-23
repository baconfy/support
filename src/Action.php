<?php

namespace Baconfy\Support;

use Baconfy\Support\Concerns\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Action
{
  /**
   * Validation rules
   */
  protected array $rules = [];

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

  /**
   * Make a static class
   */
  public static function make(): static
  {
    return app(static::class);
  }

  /**
   * Validate the data
   */
  protected function validate($payload): \Illuminate\Validation\Validator
  {
    return Validator::make($payload, $this->rules());
  }

  /**
   * Get a list of validation rules
   */
  protected function rules(): array
  {
    return $this->rules;
  }
}
