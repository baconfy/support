<?php

namespace Workbench\App\Models;

use Baconfy\Support\Concerns\Slugfy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
  use Slugfy, SoftDeletes;

  /**
   * The attributes that are mass assignable.
   */
  protected $fillable = ['name', 'another'];

  /**
   * Identifies which column should store the slug value
   */
  protected function getSlugColumn(): string
  {
    return 'another';
  }

  /**
   * Identifies which attribute should be used to generate the slug
   */
  protected function getAttributeToBeSlugified(): string
  {
    return 'name';
  }
}
