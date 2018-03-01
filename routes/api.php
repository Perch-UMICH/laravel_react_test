<?php

use Illuminate\Http\Request;
use App\Student;
use Illuminate\Support\Facades\Auth;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

// User Registration:
Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', 'UserController@details');

    Route::post('logout', 'UserController@logout');
});

// Users
Route::get('users', 'UserController@index');

// Students:
Route::get('students', 'StudentController@index');

Route::post('students','StudentController@store');

Route::put('students/{student}','StudentController@update');

Route::delete('students/{student}', 'StudentController@delete');

Route::post('students/user_id', 'StudentController@user_id');

