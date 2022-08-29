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


class LoginController extends Controller
{
    public function __invoke(UserService $userService, LoginUserRequest $request)
    {
          $response = $userService->loginUser($request);
          if($response['status'] == true){
               return response()->json($response, 200);
          }else{
               return response()->json($response, 401);
          }
    }
}
