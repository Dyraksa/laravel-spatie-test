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
    Route::post('register','Auth\RegisterController@register');
    Route::post('login','Auth\LoginController@login');
    Route::apiResource('roles','permission\RoleController');
    Route::apiResource('permission','permission\PermissionController');
    Route::apiResource('user','permission\UserController');
});
Route::group(['middleware' => 'jwt.auth','cors'], function () {
    Route::get('/me','UserController@me');
    Route::delete('/logout','UserController@Logout');

});
