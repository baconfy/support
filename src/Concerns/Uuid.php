<?php

namespace Baconfy\Support\Concerns;

use Illuminate\Support\Str;

trait Uuid
{
    public static function bootUuid(): void
    {
        self::creating(function (self $model) {
            $model->{$model->getUuidColumn()} = (string) Str::uuid();
        });
    }

    /**
     * Identifies which column should store the uuid value
     */
    protected function getUuidColumn(): string
    {
        return 'uuid';
    }
}