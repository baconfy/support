<?php

namespace Baconfy\Support;

use Illuminate\Support\Str;

trait Slugfy
{
  public function slugfy(string $string): string
  {
    $slug = Str::slug($string);

    return $slug;
  }
}