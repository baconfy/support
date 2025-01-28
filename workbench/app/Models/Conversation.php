<?php

namespace Workbench\App\Models;

use Baconfy\Support\Concerns\Slugfy;
use Baconfy\Support\Concerns\Uuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
  use Uuid;

  /**
   * The attributes that are mass assignable.
   */
  protected $fillable = ['uuid'];
}
