<?php

namespace Baconfy\Support\Concerns;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

trait Slugfy
{
  public static function bootSlugfy(): void
  {
    self::creating(function (self $model) {
      $model->{$model->getSlugColumn()} = $model->{$model->getSlugColumn()} ?? $model->slugfy($model->{$model->getAttributeToBeSlugified()});
    });
  }

  public function slugfy(string $string): string
  {
    $slug = Str::slug($string);

    $count = in_array(SoftDeletes::class, class_uses_recursive(self::class), true)
      ? $this->withTrashed()->where($this->getSlugColumn(), 'LIKE', "{$slug}%")->count()
      : $this->where($this->getSlugColumn(), 'LIKE', "{$slug}%")->count();

    return $count ? sprintf('%s-%s', $slug, (new Hashids())->encode(now()->timestamp)) : $slug;
  }

  /**
   * Identifies which column should store the slug value
   */
  protected function getSlugColumn(): string
  {
    return 'slug';
  }

  /**
   * Identifies which attribute should be used to generate the slug
   */
  protected function getAttributeToBeSlugified(): string
  {
    return 'title';
  }
}