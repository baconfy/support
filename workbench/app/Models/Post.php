<?php

namespace Workbench\App\Models;

use Baconfy\Support\Slugfy;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  use Slugfy;

  /**
   * The attributes that are mass assignable.
   */
  protected $fillable = ['title', 'slug', 'body'];
}
