<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class UserController extends Controller
{
    protected $auth;

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    public function me(Request $request)
    {
        return response()->json([
            'success'=>true,
            'data'=>$request->user(),
        ]);
    }

    public function logout(Request $request)
    {
        $this->auth->invalidate();
        return response()->json([
            'success'=>true,
            'message'=>[
                'you are logouted',
            ]
        ]);
    }
}
