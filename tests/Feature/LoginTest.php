<?php

namespace Tests\Feature;

use App\Models\UserRole;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;


    public function setUp(): void
    {
        parent::setUp();
        $this->withExceptionHandling();
        // seed the database
        $this->artisan('db:seed');
        // alternatively you can call
        // $this->seed();
    }
    public function test_login_without_email()
    {
        $response = $this->post(route('login'),[
                'password'=> "123456"
        ], ['accept' => 'application/json']);

        $response->assertStatus(422);
    }
    public function test_login_without_password()
    {
        $response = $this->post(route('login'),[
                'email'=> "a@abc.com"
        ], ['accept' => 'application/json']);

        $response->assertStatus(422);
    }

    public function test_login_with_invalid_credentials()
    {
        $response = $this->post(route('login'),[
                'email'=> "reviewer@aspire.io",
                'password'=> "123456ttt5"
        ], ['accept' => 'application/json']);

        $response->assertStatus(401);
    }


    public function test_login_valid_credentials()
    {
        $response = $this->post(route('login'),[
                'email'=> "reviewer@aspire.io",
                'password'=> "123456"
        ]);

        $response->assertStatus(200);
    }
}
