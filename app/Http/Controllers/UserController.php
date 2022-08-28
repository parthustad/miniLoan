<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\APIHelpers;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class UserController extends Controller
{

    public $registerService;

    public function __construct(UserService $userService)
    {
        $this->userService =  $userService;
    }

    public function register(RegisterUserRequest $request)
    {
          $response = $this->userService->registerUser($request);
          return response()->json($response, 200);

    }
    public function login(LoginUserRequest $request)
    {
          $response = $this->userService->loginUser($request);
          if($response['status'] == true){
               return response()->json($response, 200);
          }else{
               return response()->json($response, 401);
          }
    }




}
