<?php

namespace Baconfy\Support\Concerns;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

trait Slugfy
{
    /**
     * Boot the Slugfy trait method.
     */
    public static function bootSlugfy(): void
    {
        self::creating(function (self $model) {
            $model->{$model->getSlugColumn()} = $model->{$model->getSlugColumn()} ?? $model->slugfy($model->{$model->getAttributeToBeSlugified()});
        });
    }

    /**
     * Identifies which column should store the slug value
     */
    protected function getSlugColumn(): string
    {
        return 'slug';
    }

    /**
     * Generate a unique slug from the given string.
     */
    public function slugfy(string $string): string
    {
        $slug = Str::slug($string);

        $query = in_array(SoftDeletes::class, class_uses_recursive(self::class), true)
            ? static::withTrashed()
            : static::query();

        // Equality, not a prefix: "foo" is free even when "foo-bar" exists.
        if (! $query->where($this->getSlugColumn(), $slug)->exists()) {
            return $slug;
        }

        // The suffix carries the second and a random part, so two rows in the
        // same second no longer share it. Lowercase alphabet: a slug stays a
        // slug. The unique index remains the final guard.
        $hashids = new Hashids('', 0, 'abcdefghijklmnopqrstuvwxyz0123456789');

        return sprintf('%s-%s', $slug, $hashids->encode(now()->timestamp, random_int(0, 0xFFFFFF)));
    }

    /**
     * Identifies which attribute should be used to generate the slug
     */
    protected function getAttributeToBeSlugified(): string
    {
        return 'title';
    }
}