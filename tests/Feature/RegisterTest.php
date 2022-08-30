<?php

namespace Tests\Feature;

use App\Models\UserRole;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
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


    public function test_registration_without_email_not_allowed()
    {
        $response = $this->post(route('register'),[
            'name'=> "abc",
            'password'=> "12344",
            "role" => "ADMIN"

        ],['accept' => 'application/json']);

       $response->assertStatus(422);
    }

    public function test_registration_without_password_not_allowed()
    {
        $response = $this->post(route('register'),[
            'name'=> "abc",
            'email'=> "p@yahoo.com",
            "role" => "ADMIN"

        ],['accept' => 'application/json']);

       $response->assertStatus(422);
    }


    public function test_with_role_other_than_reviewer_and_client_not_allowed()
    {
        $response = $this->post(route('register'),[
                'name'=> "abc",
                'email'=> "p@yahoo.com",
                'password'=> "12344",
                "role" => "ADMIN"
        ], ['accept' => 'application/json']);

        $response->assertStatus(422);
    }


    public function test_with_role_client()
    {
        $response = $this->post(route('register'),[

                'name'=> "abc",
                'email'=> "p@yahoo.com",
                'password'=> "12344",
                "role" => "CLIENT"
        ],['accept' => 'application/json']);

        $response->assertStatus(200);
    }


    public function test_with_role_reviewer()
    {
        $response = $this->post(route('register'),[
                'name'=> "abc",
                'email'=> "p@yahoo.com",
                'password'=> "12344",
                "role" => "REVIEWER"
        ],['accept' => 'application/json']);

        $response->assertStatus(200);
    }


}
