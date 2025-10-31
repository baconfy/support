<?php

namespace Baconfy\Support\Tests\Fakes;

use Baconfy\Support\Concerns\Enums\ToArray;

enum FakeEnum: string
{
    use ToArray;

    case ACTIVE = 'active';
    case PENDING = 'pending';
    case DISABLED = 'disabled';
}
