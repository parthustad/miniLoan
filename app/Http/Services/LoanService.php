<?php
namespace App\Http\Services;

use App\Http\Helpers\APIHelpers;
use App\Http\Requests\ApproveLoanRequest;
use App\Http\Requests\LoanRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\AuthUserResource;
use App\Http\Resources\LoanResource;
use App\Models\User;
use App\Models\Loan;
use App\Models\SchedulePayment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LoanService
{
    protected $response = [];

    public function createLoan(LoanRequest $request)
    {
        $amount =  $request->amount;
        $term =  $request->term;
        $min_payment = $amount / $term;
        $min_payment = number_format($min_payment, 2, ".", "");

        $loanData = [
            'amount' =>  $request->amount,
            'term' =>   $request->term,
            'loan_status' => 'PENDING' ,
            'min_payment' =>  $min_payment,
            'reviewer_id' => 1
        ];
        return APIHelpers::createResponse(true,'Loan Created',new LoanResource( $request->user()->loans()->create($loanData)));

    }

    public function getUserLoan($request){
        return APIHelpers::createResponse(true,'Loan loaded',LoanResource::collection($request->user()->loans()->get()));
    }

    public function approveLoan(ApproveLoanRequest $request){

        $loan = Loan::where('id',  $request->id)->first();

        if($loan->loan_status == "PENDING"){
            $amount =  $loan->amount;
            $term =  $loan->term;
            $min_payment = $amount / $term;


            $loan =  Loan::where('id', $request->id)
            ->update(['loan_status' => 'APPROVED','min_payment'=> $min_payment,'reviewer_id'=>Auth::user()->id]);

            $this->createSchedulePayment( $request->id );
        }else{
            $this->response['data'] = new LoanResource($loan);
            return APIHelpers::createResponse(false,'Loan is not pending',new LoanResource($loan));
        }
        $loan = Loan::where('id',  $request->id)->first();

        return APIHelpers::createResponse(true,'Loan approved',new LoanResource($loan));


    }

    protected function createSchedulePayment( $id ){

        if( empty(SchedulePayment::where('loan_id', $id)->get()->toArray() )){

            $loan = Loan::where('id',  $id)->first();
            $amount =  $loan->amount;
            $term =  $loan->term;
            $client_id =  $loan->client_id;
            $status = "UNPAID";

            $reminder = $amount / $term;
            $reminder = number_format($reminder, 2, ".", "");

            $schduled_at = Carbon::now();

            $sumReminder = 0;
            for($i=1;$i<=$term;$i++){
                if($i == $term){
                    $reminder = $amount - $sumReminder;
                }
                $schduled_at = $schduled_at->addDay(7);
                SchedulePayment::create([
                    'amount' =>  $reminder,
                    'term' =>  $term,
                    'client_id' =>  $client_id,
                    'status' => 'UNPAID' ,
                    'loan_id'=> $id,
                    'scheduled_at' =>  $schduled_at
                ]);
                $sumReminder +=  $reminder;
            }
        }
    }
    //


}
