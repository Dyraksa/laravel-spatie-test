<?php

namespace App\Http\Controllers\api\Auth;

use App\Http\Controllers\Controller;
use App\models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class RegisterController extends Controller
{

    use RegistersUsers;

    protected $auth;

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }
    public function register(Request $request)
    {
        $validate = $this->validator($request->all());
        if(!$validate->fails()){
            $user = $this->create($request->all());
            $token = $this->auth->attempt($request->only('email','password'));
            return response()->json([
                'success'=>true,
                'data'=>$user,
                'token'=>$token,
            ],200);
        }else{
            return response()->json([
                'success'=> false,
                'errors'=>$validate->errors(),
            ],422);
        }
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
