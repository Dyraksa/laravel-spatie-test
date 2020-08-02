<?php

namespace App\Http\Controllers\api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $auth;

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    public function login(Request $request)
    {
        $this->validator($request->all())->validate();

        if($this->hasTooManyLoginAttempts($request)){
            $this->fireLockoutEvent($request);
            return response()->json([
                'success'=>false,
                'errors'=>[
                    'You`ve been locked out'
                ]
            ]);
        }
        try
        {
            if(!$token = JWTAuth::attempt($request->only('email','password'))){
                return response()->json([
                    'success'=>true,
                     'errors'=>[
                         'email'=>[
                             'Invalid Email or Password'
                         ]
                     ]
                ],422);
            }
        }
        catch(JWTException $e)
        {
            return response()->json([
                'success'=>true,
                 'errors'=>[
                     'email'=>[
                         'Invalid Email or Password'
                     ]
                 ]
            ],422);
        }
        return response()->json([
            'success'=>true,
            'data'=>$request->user(),
            'token'=>$token
        ],200);
    }

    protected function validator(array $data)
    {
        return Validator::make($data,[
            'email'=>'required',
            'password'=>'required',
        ]);
    }
}
