<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Category;
use Illuminate\Support\Facades\Validator;

class CateogryController extends Controller
{
    public function index(Request $request)
    {
        $cat = Category::all();
        return response()->json([
            'success'=>true,
            'data'=>$cat,
        ]);
    }

    public function show(Request $request,$id)
    {
        $id = $request->id;
        $show = Category::find($id);
        return response()->json([
            'success'=>true,
            'data'=>$show
        ]);
    }

    public function edit(Request $request,$id)
    {
        $id = $request->id;
        $catid = Category::find($id);
        return response()->json([
            'success'=>true,
            'data'=>$catid,
        ]);
    }

    public function store(Request $request)
    {
        $validate = $this->validator($request->all());
        if(!$validate->fails()){
            $cat = Category::create([
                'name'=>$request->name,
                'description'=>$request->description,
            ]);
            return response()->json([
                'success'=>true,
                'data'=>[
                    'upload is successs',
                ]
            ]);
        }else
        {
            return response()->json([
                'success'=>false,
                'errors'=>$validate->errors(),
            ]);
        }

    }

    public function create(Request $request)
    {

    }

    public function destroy(Request $request, $id)
    {
        $id = $request->id;
        Category::destroy($id);
        return response()->json([
            'success'=>true,
            'data'=>[
                'delete is success'
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $validate = $this->validator($request->all());
        $id = $request->id;
        if(!$validate->fails()){
            $catup = Category::find($id);
            $catup->update([
                'name'=>$request->name,
                'description'=>$request->description,
            ]);
            return response()->json([
                'success'=>true,
                'data'=>[
                    'upload is successs',
                ]
            ]);
        }else
        {
            return response()->json([
                'success'=>false,
                'errors'=>$validate->errors(),
            ]);
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data,[
            'name'=>'required',
            'description'=>'required',
        ]);
    }
}
