<?php

namespace Baconfy\Support\Tests\Fake;

use Baconfy\Support\Concerns\Runner;

class ConcatenateTwoStrings
{
    use Runner;

    public function handle($one, $two): string
    {
        return sprintf("%s %s", $one, $two);
    }
}