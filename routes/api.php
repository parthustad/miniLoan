<?php

use Carbon\Carbon;
use App\Models\Loan;
use App\Models\User;
use App\Models\Installments;
use Illuminate\Http\Request;
use App\Models\SchedulePayment;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoansController;
use App\Http\Services\InstallmentService;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\LoginController;

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
Route::post('/register', RegisterController::class)->name('register');
Route::post('/login', LoginController::class)->name('login');


Route::get('/reset', function(Request $request)
{

    //Artisan::call('cache:clear');
    Installments::where('loan_id',1)->delete();
    SchedulePayment::where('loan_id',1) ->update(['status' => 'UNPAID','paid_at'=>NULL]);

    $updateLoan =  Loan::where('id', 1)
    ->update(['total_paid' => 0,'extra_amount'=>0,'loan_status'=>"APPROVED"]);


});


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/loans', [LoansController::class,'store'])->name('loans.create');
    Route::get('/loans', [LoansController::class,'index'])->name('loans.get');
    Route::put('/loans', [LoansController::class,'statusUpdate'])->name('loans.statusUpdate');

    Route::middleware('createInstallment')->group(function () {
        Route::post('/installments', [InstallmentController::class,'store'])->name('installments.create');
    });
});
