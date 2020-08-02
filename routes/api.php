<?php

use App\Http\Controllers\api\UserController;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => '/auth',['middleware'=>'throttle:20,5']],function () {
    Route::post('login','Auth\LoginController@login');
});
Route::group(['middleware' => 'jwt.auth','cors'], function () {
    Route::get('/me','UserController@me');
    Route::delete('/logout','UserController@Logout');
    //started roles permission
    Route::get('roles/index','permission\RoleController@index');
    Route::get('roles/create','permission\RoleController@create');
    Route::post('roles/store','permission\RoleController@store');
    Route::get('roles/show/{id}','permission\RoleController@show');
    Route::get('roles/edit/{id}','permission\RoleController@edit');
    Route::put('roles/update/{id}','permission\RoleController@update');
    Route::delete('roles/delete/{id}','permission\RoleController@destroy');
    //end roles permission
    //started permission here
    Route::get('permission/index','permission\PermissionController@index');
    Route::get('permission/create','permission\PermissionController@create');
    Route::get('permission/show/{id}','permission\PermissionController@show');
    Route::post('permission/store','permission\PermissionController@store');
    Route::delete('permission/destroy/{id}','permission\PermissionController@destroy');
    Route::get('permission/edit/{id}','permission\PermissionController@edit');
    Route::put('permission/update/{id}','permission\PermissionController@update');
    //end here
    //stared user name
    Route::get('user/index','permission\UserController@index');
    Route::get('user/create','permission\UserController@create');
    Route::post('user/store','permission\UserController@store');
    Route::get('user/edit/{id}','permission\UserController@edit');
    Route::put('user/update/{id}','permission\UserController@update');
    Route::delete('user/destroy/{id}','permission\UserController@destroy');
    //end here

    //started cateogry controller
    Route::get('category','CateogryController@index');
    Route::get('category/edit/{id}','CateogryController@edit');
    Route::get('category/show/{id}','CateogryController@show');
    Route::get('category/create','CateogryController@create');
    Route::post('category/store','CateogryController@store');
    Route::put('category/update/{id}','CateogryController@update');
    Route::delete('category/delete/{id}','CateogryController@destroy');
    //end here

    //stated product controller

    //end here
});
