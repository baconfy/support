<?php

namespace Baconfy\Support\Concerns;

use Illuminate\Support\Str;

trait Ulid
{
    /**
     * Boot the ULID functionality for the model.
     */
    public static function bootUlid(): void
    {
        self::creating(function (self $model) {
            $model->{$model->getUlidColumn()} = (string) Str::ulid();
        });
    }

    /**
     * Identifies which column should store the ulid value
     */
    protected function getUlidColumn(): string
    {
        return 'ulid';
    }
}
