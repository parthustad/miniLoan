<?php

namespace Tests;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication,RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

    }

}
