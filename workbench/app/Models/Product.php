<?php

namespace Workbench\App\Models;

use Baconfy\Support\Slugfy;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use Slugfy;

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
