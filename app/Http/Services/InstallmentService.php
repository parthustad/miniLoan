<?php
namespace App\Http\Services;

use Carbon\Carbon;
use App\Models\Loan;
use App\Models\User;
use App\Models\Installments;
use App\Models\SchedulePayment;
use App\Http\Helpers\APIHelpers;
use App\Http\Requests\LoanRequest;
use App\Http\Resources\LoanResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginUserRequest;
use App\Http\Resources\AuthUserResource;
use App\Http\Requests\ApproveLoanRequest;
use App\Http\Requests\InstallmentRequest;
use App\Http\Requests\RegisterUserRequest;

class InstallmentService
{
    public function createInstallment(InstallmentRequest $request) : array{

        $amount = $request->amount;
        $loan = Loan::where('id',  $request->loan_id)->first();

        $response = [];

        if($this->isApproved($loan) == false){
            return APIHelpers::createResponse(false,'Loan is '.$loan->loan_status,new LoanResource($loan));
        }
        if($this->isAmountGreaterLoanAmount($loan, $amount) == false){
            return APIHelpers::createResponse(false,'Amount can not be greater than loan amount',new LoanResource($loan));
        }
        if($this->isLoanSettled($loan) == true){
            return APIHelpers::createResponse(false,'Loan is already setlled',new LoanResource($loan));
        }
        if($amount > ($loan->amount - $loan->total_paid)){
            return APIHelpers::createResponse(false,'Amount is greater than remaining amout',new LoanResource($loan));
        }

        //Last Installment
        if($this->isLastPayment($loan)){

            if($this->isLastPaymentValidated($loan,$amount)){
                $this->addInstallment($loan,$amount);
                $this->setTotalPaidAmount($loan,$amount);
                $this->setLastPayment($loan);
                $this->setLoanAsPaid($loan);

                $loan = Loan::where('id',  $request->loan_id)->first();
                return APIHelpers::createResponse(true,'Loan is settled',new LoanResource($loan));
            }else{
                return APIHelpers::createResponse(false,'Last payment should be euqal to remaining amount',new LoanResource($loan));
            }
        }

        if($this->isGreaterOrEqalToMinPayment($loan, $amount) == false){
            return APIHelpers::createResponse(false,'Amount should be greater or equal to installments',new LoanResource($loan));
        }


        //One ShortPayment
        if($loan->amount ==  $amount){
            $this->addInstallment($loan,$amount);
            $this->setTotalPaidAmount($loan,$amount);
            $this->setSchedulePaymentPaidStatus($loan,$amount);
            $this->setLoanAsPaid($loan);
            $loan = Loan::where('id',  $request->loan_id)->first();
            return APIHelpers::createResponse(true,'Loan is paid in One short',new LoanResource($loan));

        }

        $this->addInstallment($loan,$amount);
        $this->setTotalPaidAmount($loan,$amount);
        $this->setSchedulePaymentPaidStatus($loan,$amount);

        $loan = Loan::where('id',  $request->loan_id)->first();

        if($this->isLoanSettled($loan) == true){
            $this->setLoanAsPaid($loan);
            $loan = Loan::where('id',  $request->loan_id)->first();
            return APIHelpers::createResponse(true,'Loan is settled',new LoanResource($loan));
        }

        $loan = Loan::where('id',  $request->loan_id)->first();
        return APIHelpers::createResponse(true,'Installment Added',new LoanResource($loan));

    }

    protected function isApproved($loan) :bool {
        if($loan->loan_status == "APPROVED"){
            return true;
        }else{
            return false;
        }
    }

    protected function isAmountGreaterLoanAmount($loan,$amount) :bool {

        if($amount <= $loan->amount){
            return true ;
        }else{
            return false;
        }
    }

    protected function isGreaterOrEqalToMinPayment($loan,$amount) :bool {

        if ($amount >= $loan->min_payment ){
            return true;
        }else{
            return false;
        }
    }

    protected function isLoanSettled($loan) :bool {
        $paidInstallments = SchedulePayment::where(['loan_id'=> $loan->id,'status'=>"PAID"])->get()->count();

        if($loan->amount == $loan->total_paid && $paidInstallments == $loan->term){
            return true;
        }
        return false;

    }

    protected function isLastPayment($loan) :bool {
        $unpaid = SchedulePayment::where(['loan_id'=>$loan->id,'status'=>"UNPAID"])->get()->count();
        if($unpaid == 1 ){
            return true;
        }
        return false;
    }

    protected function isLastPaymentValidated($loan,$amount) :bool {

        if( $amount >= ($loan->amount - $loan->total_paid ) ){
            return true;
        }
        return false;
    }

    protected function setLastPayment($loan) : void{
        $schedulePayment = SchedulePayment::where( ['loan_id'=> $loan->id,'status'=>"UNPAID"])
        ->update(['status'=>'PAID','paid_at' => Carbon::now()]);
    }

    protected function addInstallment($loan,$amount) :bool {

        // Add installment in talbe
        $installment = new Installments();
        $installment->loan_id = $loan->id;
        $installment->amount = $amount;
        $installment->client_id = $loan->client_id;
        $installment->save();
        return true;
    }

    protected function setLoanAsPaid($loan) : void{
        $updateLoan =  Loan::where('id', $loan->id)
        ->update(['disbursed_at' =>  Carbon::now(),'loan_status'=>'PAID','extra_amount'=>0.00]);
    }

    protected function setTotalPaidAmount($loan,$amount) : void{

        $total_paid = Installments::where('loan_id', $loan->id)->sum('amount');

        // Update paid amount in loan table
        $updateLoan =  Loan::where('id', $loan->id)
        ->update(['total_paid' =>  $total_paid]);

    }

    protected function setSchedulePaymentPaidStatus($loan,$amount) : void{

        $min_payment = $loan->min_payment;
        $fraction = (int) (($amount + $loan->extra_amount) / $min_payment );

        for($i=1;$i<=$fraction;$i++){

            //Marking status to paid in scheduled payment table
            $schedulePayment = SchedulePayment::where([
                'loan_id'=> $loan->id,
                'status'=>"UNPAID"
                ])->first();
                if($schedulePayment != NULL){
                    $schedulePayment->status = "PAID";
                    $schedulePayment->paid_at = Carbon::now();
                    $schedulePayment->save();
                }

        }

        $extra_amount = ($amount + $loan->extra_amount) - ($fraction * $min_payment );
        $updateLoan =  Loan::where('id', $loan->id)
        ->update(['extra_amount' =>  $extra_amount]);


    }
}
