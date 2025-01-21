<?php

namespace Baconfy\Support\Tests\Fake;

use Baconfy\Support\Action;

class ConcatenateTwoStrings extends Action
{
  public function handle($one, $two): string
  {
    return sprintf("%s %s", $one, $two);
  }
}