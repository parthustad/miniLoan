<?php
namespace App\Http\Services;

use App\Http\Helpers\APIHelpers;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\AuthUserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public $response = [];


    public function registerUser(RegisterUserRequest $request) : array
    {
        User::create( $request->all());
        return APIHelpers::createResponse(true,'User Created Sucessfully',$this->getLoggedinUserByEmail($request->email));
    }

    public function loginUser(LoginUserRequest $request)  : array
    {
        if(!Auth::attempt($request->only(['email', 'password']))){
            return APIHelpers::createResponse(false,'Invalid Credentials',[]);
        }else{
            return APIHelpers::createResponse(true,'Loggedin Sucessfully',$this->getLoggedinUserByEmail($request->email));
        }
    }

    protected function getLoggedinUserByEmail($email) : AuthUserResource
    {
        $user = User::firstWhere('email', $email);
        $user->token =  $user->createToken("API TOKEN")->plainTextToken;

        return new AuthUserResource($user);
    }

}
