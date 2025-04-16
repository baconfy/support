<?php

namespace Baconfy\Support\Tests\Fake;

use Baconfy\Support\Concerns\TransactionRunner;

class TransactionalAction
{
    use TransactionRunner;

    public function handle(): bool
    {
        return true;
    }
}