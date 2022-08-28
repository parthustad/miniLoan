<?php

namespace App\Http\Controllers;

use App\Http\Helpers\APIHelpers;
use App\Http\Requests\ApproveLoanRequest;
use App\Http\Requests\LoanRequest;
use App\Http\Resources\LoanResource;
use App\Http\Services\LoanService;
use App\Models\Loan;

use Illuminate\Http\Request;


class LoansController extends Controller
{
    protected $loanService ;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;

    }
    public function store(LoanRequest $request){
        $response = $this->loanService->createLoan($request);
        return response()->json($response, 200);
    }
    public function index(Request $request){
        $response =  $this->loanService->getUserLoan($request);
        return response()->json($response, 200);
    }
    
    public function statusUpdate(ApproveLoanRequest $request){
        $response = $this->loanService->approveLoan($request);       
        return response()->json($response, 200);
    }

}
