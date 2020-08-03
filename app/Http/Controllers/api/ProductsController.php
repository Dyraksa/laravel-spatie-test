<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\models\Category;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {

    }

    public function create(Request $request)
    {
        $cate = Category::select('*')->get();
        return $cate;
    }

    public function edit(Request $request, $id)
    {
    }

    public function show(Request $request, $id)
    {

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'description'=>'required',
            'qauntity'=>'required',
            'price'=>'required',
            'image'=>'required',
            'category_id'=>'required',
        ]);


    }

    public function update(Request $request, $id)
    {
    }

    public function destroy(Request $request, $id)
    {
    }
}
