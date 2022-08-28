<?php

use App\Http\Controllers\LoansController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InstallmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Models\Loan;
use App\Models\User;
use App\Http\Services\InstallmentService;
use App\Models\SchedulePayment;
use App\Models\Installments;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register', [UserController::class,'register'])->name('user.register');
Route::post('/login', [UserController::class,'login'])->name('login');


Route::get('/status', function(Request $request){
    $installmentService = new InstallmentService();
    $loan =  Loan::where('id', 1)->first();

    $msg = "-".$loan->amount."-";
    $msg .= "-".$loan->paid_amount."-";
    $msg .= "-". ($loan->amount - $loan->total_paid) ."-";
    return $msg ;

    //return $installmentService->isLoanSettled($loan);
});

Route::get('/reset', function(Request $request)
{

    //Artisan::call('cache:clear');
    Installments::where('loan_id',1)->delete();
    SchedulePayment::where('loan_id',1) ->update(['status' => 'UNPAID','paid_at'=>NULL]);

    $updateLoan =  Loan::where('id', 1)
    ->update(['total_paid' => 0,'extra_amount'=>0,'loan_status'=>"APPROVED"]);


});

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/loans', [LoansController::class,'store'])->name('loan.create');
    Route::get('/loans', [LoansController::class,'index'])->name('loan.create');
    Route::put('/loans', [LoansController::class,'statusUpdate'])->name('loan.statusUpdate');

    Route::middleware('createInstallment')->group(function () {
        Route::post('/installments', [InstallmentController::class,'store'])->name('installment.create');
    });
});
