<?php

namespace App\Http\Controllers\api\permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *function __construct()
    {
        $this->middleware('permission:user-list');
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $data = User::with('roles')->get();
        return response()->json([
            'success'=>true,
            'user'=>$data,
        ]);
    }

    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return response()->json([
            'success'=>true,
            'roles'=>$roles,
        ]);
    }

    public function store(Request $request)
    {
        $validate = $this->validator($request->all());
        if(!$validate->fails()){
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $user = User::create($input);
            $user->assignRole($request->input('roles'));
            return response()->json([
                'success'=>false,
                'message'=>[
                    'upload is success',
                ]
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'errors'=>$validate->errors(),
            ]);
        }

    }
    public function edit(Request $request,$id)
    {
        $id = $request->id;
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();

        return response()->json([
            'success'=>true,
            'user'=>$user,
            'roles'=>$roles,
            'userrole'=>$userRole,
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validate = $this->validator($request->all());
        if(!$validate->fails()){
            $id = $request->id;
            $input = $request->all();
            if(!empty($input['password'])){
                $input['password'] = Hash::make($input['password']);
            }else{
                $input = array_except($input,array('password'));
            }
            $user = User::find($id);
            $user->update($input);
            DB::table('model_has_roles')->where('model_id',$id)->delete();
            $user->assignRole($request->input('roles'));
            return response()->json([
                'success'=>true,
                'message'=>[
                    'update is success'
                ]
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'errrors'=>$validate->errors(),
            ],422);
        }

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $id = $request->id;
        User::find($id)->delete();
        return response()->json([
            'success'=>true,
            'message'=>[
                'delete is successfully'
            ]
        ]);
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,',
            'password' => 'required',
            'roles' => 'required'
        ]);
    }

}
