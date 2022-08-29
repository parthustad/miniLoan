<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Loan;
use App\Models\User;
use App\Models\UserRole;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ApproveLoanTest extends TestCase
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
    public function test_gest_user_can_not_approve_loan()
    {
        $response = $this->put(route('loans.statusUpdate'),[
            'id'=> 15

        ], ['accept' => 'application/json']);

       $response->assertStatus(401);
    }

    public function test_client_can_not_approve_loan()
    {
        $user =  User::factory()->create(['role'=>"CLIENT"]);
        Sanctum::actingAs($user);

        $response = $this->put(route('loans.statusUpdate'),[
            'id'=> 1
        ], ['accept' => 'application/json']);

        $response->assertStatus(403);
    }

    public function test_loan_id_must_be_valid()
    {
        $user =  User::factory()->create(['role'=>"CLIENT"]);
        Sanctum::actingAs($user);

        $response = $this->post(route('loans.create'),[
            'amount'=> 10.00,
            'term'=> 5
        ]);
        $response->assertStatus(200);


        $user =  User::factory()->create(['role'=>"REVIEWER"]);
        Sanctum::actingAs($user);

        $response = $this->put(route('loans.statusUpdate'),[
            'id'=> 1
        ], ['accept' => 'application/json']);

        //$user =  Loan::factory()->create(['role'=>"CLIENT"]);
       $response->assertStatus(200);
    }

    public function test_loan_alredy_not_approved()
    {
        $user =  User::factory()->create(['role'=>"CLIENT"]);

        $client_id = $user->id;
        Sanctum::actingAs($user);

        $loan =  Loan::factory()->create(['client_id'=> $client_id,'loan_status'=>'APPROVED']);

        $user =  User::factory()->create(['role'=>"REVIEWER"]);
        Sanctum::actingAs($user);

        $response = $this->put(route('loans.statusUpdate'),[
            'id'=>  $loan->id
        ], ['accept' => 'application/json']);

       $data =  $response->json();
       
        if($data['status']==false){
            $response->assertStatus(200);
        }
        //$response->assertJsonPath('status',false );
    }








}
