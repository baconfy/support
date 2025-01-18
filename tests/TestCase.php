<?php

namespace Baconfy\Support\Tests;

use function Orchestra\Testbench\workbench_path;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
  /**
   * Define database migrations.
   */
  protected function defineDatabaseMigrations(): void
  {
    $this->loadMigrationsFrom(workbench_path('database/migrations'));
  }
}
