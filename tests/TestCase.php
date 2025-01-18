<?php

namespace Baconfy\Support\Tests;

use Orchestra\Testbench\Attributes\WithMigration;

#[WithMigration]
abstract class TestCase extends \Orchestra\Testbench\TestCase
{
}
