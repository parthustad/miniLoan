<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;


use App\Models\Loan;
use App\Models\User;
use App\Models\UserRole;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_that_true_is_true()
    {
        $this->assertTrue(true);
    }
}
