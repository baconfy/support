<?php

namespace Baconfy\Support\Concerns;

use Illuminate\Support\Facades\DB;

trait TransactionRunner
{
    use Runner;

    /**
     * Action entry point
     */
    public static function run(mixed ...$arguments): mixed
    {
        return DB::transaction(function () use ($arguments) {
            return static::make()->handle(...$arguments);
        });
    }
}
