<?php

use Baconfy\Support\Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature')
    ->in('Unit');