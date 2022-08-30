<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LoanTest extends TestCase
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
    public function test_guest_user_can_not_create_role()
    {
        $response = $this->post(route('loans.create'),[
            'amount'=> 10.00,
            'term'=> 5
        ], ['accept' => 'application/json']);
       $response->assertStatus(401);
    }
    public function test_role_other_than_client_can_not_create_loan()
    {
        $user =  User::factory()->create(['role'=>"REVIEWER"]);
        Sanctum::actingAs($user);

        $response = $this->post(route('loans.create'),[
            'amount'=> 10.00,
            'term'=> 5
        ]);

        $response->assertStatus(403);
    }

    public function test_without_loan_amount_not_allowed()
    {
        $user =  User::factory()->create(['role'=>"CLIENT"]);
        Sanctum::actingAs($user);

        $response = $this->post(route('loans.create'),[
            'term'=> 5
        ], ['accept' => 'application/json']);

       $response->assertStatus(422);
    }

    public function test_user_with_client_role_can_create_loan()
    {
        $reviewer =  User::factory()->create(['role'=>"REVIEWER"]);

        $user =  User::factory()->create(['role'=>"CLIENT"]);
        Sanctum::actingAs($user);

        $response = $this->post(route('loans.create'),[
            'amount'=> 10.00,
            'term'=> 5
        ]);

        $response->assertStatus(200);
    }








}
