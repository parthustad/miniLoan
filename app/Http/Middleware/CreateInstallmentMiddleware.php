<?php

namespace App\Http\Middleware;

use App\Http\Helpers\APIHelpers;
use App\Models\Loan;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreateInstallmentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $client_id = Auth::id();
        $loan_id = $request->loan_id ;
        $loan = Loan::where(['id'=>$loan_id,'client_id'=>$client_id])->get()->toArray();

        if(empty($loan)){
            $response = APIHelpers::createResponse(false,'Unathorize access',[]);
            return response()->json($response, 403);

        }
        return $next($request);
    }
}
