<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\InstallmentService;
use App\Http\Requests\InstallmentRequest;
class InstallmentController extends Controller
{
    protected $installmentService;

    public function __construct(InstallmentService $installmentService)
    {
        $this->installmentService = $installmentService;
    }
    public function store(InstallmentRequest $request){
        $response = $this->installmentService->createInstallment($request);
        return response()->json($response, 200);
    }
}
