<?php

namespace Baconfy\Support\Tests\Fake;

use Baconfy\Support\Action;
use Baconfy\Support\Concerns\Transaction;

class TransactionalAction extends Action
{
  use Transaction;

  public function handle(): bool
  {
    return true;
  }
}