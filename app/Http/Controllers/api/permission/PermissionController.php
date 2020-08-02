<?php

namespace App\Http\Controllers\api\permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\RoleHasPermission;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{

    /**
     * Display a listing of the resource.
     *function __construct()
    {
         $this->middleware('permission:permission-list');
         $this->middleware('permission:permission-create', ['only' => ['create','store']]);
         $this->middleware('permission:permission-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $permissions = Permission::all();
        return response()->json([
            'success' => true,
            'data' => $permissions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::get();
        return response()->json([
            'success' => true,
            'data' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $this->validator($request->all());
        if (!$validate->fails()) {
            $name = strtolower($request['name']);
            $permission = new Permission();
            $permission->name = $name;
            $roles = $request['roles'];
            $permission->save();
            if (!empty($request['roles'])) {
                foreach ($roles as $role) {
                    $r = Role::where('id', '=', $role)->firstOrFail();
                    $permission = Permission::where('name', '=', $name)->first();
                    $r->givePermissionTo($permission);
                }
            }
            return response()->json([
                'success' => true,
                'message' => [
                    'upload is successfulyy'
                ]
            ]);
        } else {
            return response()->json([
                'success' => true,
                'errors' => $validate->errors(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $id = $request->id;
        $permission = Permission::find($id);
        $permissionRoles = Role::join("role_has_permissions as r", "r.role_id", "=", "roles.id")->where("r.permission_id", $id)->get();
        return response()->json([
            'success' => true,
            'data' => [
                'permission' => $permission,
                'permissionroles' => $permissionRoles,
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $id = $request->id;
        $permission = Permission::findOrFail($id);
        $roles = Role::get();
        $permissionRoles = RoleHasPermission::where("permission_id", $id)->pluck('role_id', 'role_id')->all();
        return view('admin.permissions.edit', compact('permission', 'roles', 'permissionRoles'));
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
        $id = $request->id;
        $validate = $this->validator($request->all());
        if (!$validate->fails()) {
            $permission = Permission::find($id);
            $permission->name = $request->input('name');
            $permission->save();
            $permission->syncRoles($request->input('role'));
            return response()->json([
                'success' => true,
                'message' => [
                    'update is successfully',
                ]
            ]);
        } else {
            return response()->json([
                'success' => true,
                'errors' => $validate->errors(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $id = $request->id;
        $permission = Permission::findOrFail($id);
        if ($permission->name == "admin") {
            return response()->json([
                'message' => [
                    'Cannot delete this Permission!'
                ]
            ]);
        }
        $permission->delete();

        return response()->json([
            'success' => true,
            'message' => [
                'permission delete is successfully',
            ]
        ]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:40'
        ]);
    }
}
