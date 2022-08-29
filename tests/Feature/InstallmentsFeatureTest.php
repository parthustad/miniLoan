<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Loan;
use App\Models\User;
use App\Models\UserRole;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class InstallmentsFeatureTest extends TestCase
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
    public function test_gest_user_can_not_create_installments()
    {
        $response = $this->post(route('installments.create'),[
            'amount'=> 15,
            'loan_id' => 1
        ], ['accept' => 'application/json']);

       $response->assertStatus(401);
    }

    public function test_reviewer_can_not_create_loan()
    {
        $user =  User::factory()->create(['role'=>"REVIEWER"]);
        Sanctum::actingAs($user);

        $response = $this->post(route('installments.create'),[
            'amount'=> 15,
            'loan_id' => 1
        ], ['accept' => 'application/json']);

        $response->assertStatus(403);
    }

    public function test_loan_id_must_be_valid()
    {
        $user =  User::factory()->create(['role'=>"CLIENT"]);
        Sanctum::actingAs($user);

        $response = $this->post(route('installments.create'),[
            'amount'=> 15,
            'loan_id' => 45444
        ], ['accept' => 'application/json']);

       $response->assertStatus(403);
    }

    public function test_loan_alredy_not_paid()
    {
        $client =  User::factory()->create(['role'=>"CLIENT"]);
        Sanctum::actingAs($client);

        $loan =  Loan::factory()->create(['client_id'=>  $client->id,'loan_status'=>'PAID','amount'=>10,'term'=>3]);

        $response = $this->post(route('installments.create'),[
            'amount'=> 4,
            'loan_id' => $loan->id
        ], ['accept' => 'application/json']);


        $response->assertJsonPath('message','Loan is PAID' );
    }

    public function test_loan_must_be_approved()
    {
        $client =  User::factory()->create(['role'=>"CLIENT"]);
        Sanctum::actingAs($client);

        $loan =  Loan::factory()->create(['client_id'=>  $client->id,'loan_status'=>'PENDING','amount'=>10,'term'=>3]);;


        $response = $this->post(route('installments.create'),[
            'amount'=> 4,
            'loan_id' => $loan->id
        ], ['accept' => 'application/json']);


        $response->assertJsonPath('message','Loan is PENDING' );
    }

    public function test_isAmountGreaterLoanAmount()
    {
        $client =  User::factory()->create(['role'=>"CLIENT"]);
        Sanctum::actingAs($client);

        $loan =  Loan::factory()->create(['client_id'=>  $client->id,'loan_status'=>'APPROVED','amount'=>10,'term'=>3]);


        $response = $this->post(route('installments.create'),[
            'amount'=> 15,
            'loan_id' => $loan->id
        ], ['accept' => 'application/json']);


        $response->assertJsonPath('message','Amount can not be greater than loan amount' );
    }

    public function test_single_installment()
    {
        $client =  User::factory()->create(['role'=>"CLIENT"]);
        Sanctum::actingAs($client);

        $response = $this->post(route('loans.create'),[
            'amount'=> 10.00,
            'term'=> 5
        ]);

        $loan_id=$response->json()['data']['id'];

        $reviewer =  User::factory()->create(['role'=>"REVIEWER"]);
        Sanctum::actingAs($reviewer);

        $response = $this->put(route('loans.statusUpdate'),[
            'id'=>$loan_id,
        ], ['accept' => 'application/json']);


        Sanctum::actingAs($client);
        $response = $this->post(route('installments.create'),[
            'amount'=> 3.33,
            'loan_id' => $loan_id,
        ], ['accept' => 'application/json']);


        if($response->json()['data']["total_paid"] == 3.33){
            $response->assertStatus(200);
        }else{
            dd($response->json()['message']);
        }

    }

    public function test_oneShort_installment(){
        $response = $this->createInstallmenttest(10,3,[10]);
        if($response[0]->json()['status']==true){
            $response[0]->assertStatus(200);
        }
        else{
            dd($response[0]->json()['message']);
        }
    }
    public function test_multiple_installments(){
        $loan_amount  = 10;
        $term = 7;
        $amounts = [9.5,0.5];
        $arrToCheck = count($amounts) - 1;

        $response = $this->createInstallmenttest($loan_amount,$term,$amounts);

        if($response[$arrToCheck]->json()['status']==true){
            $response[$arrToCheck]->assertStatus(200);
         }
        else{
            dd(($response[$arrToCheck])->json());
        }

    }

    public function createInstallmenttest($loanAmount,$loanTerm,$amountsToPay){
        $client =  User::factory()->create(['role'=>"CLIENT"]);
        Sanctum::actingAs($client);

        $createLoan = $this->post(route('loans.create'),[
            'amount'=> $loanAmount,
            'term'=> $loanTerm
        ]);

        $loan_id=$createLoan->json()['data']['id'];

        $reviewer =  User::factory()->create(['role'=>"REVIEWER"]);
        Sanctum::actingAs($reviewer);

        $approveLoan = $this->put(route('loans.statusUpdate'),[
            'id'=>$loan_id,
        ], ['accept' => 'application/json']);


        Sanctum::actingAs($client);
        if(is_array($amountsToPay)){
            foreach($amountsToPay as $amountToPay){
                $response[] = $this->post(route('installments.create'),[
                    'amount'=> $amountToPay,
                    'loan_id' => $loan_id,
                ], ['accept' => 'application/json']);

            }
        }else{
            $response = $this->post(route('installments.create'),[
                'amount'=> $amountsToPay,
                'loan_id' => $loan_id,
            ], ['accept' => 'application/json']);

        }

        return $response;

    }




}
