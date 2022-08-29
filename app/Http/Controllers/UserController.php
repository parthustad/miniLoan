<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Helpers\APIHelpers;
use App\Http\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\RegisterUserRequest;


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
