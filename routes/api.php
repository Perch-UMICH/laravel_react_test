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

// Note: calls to routes protected by auth:api require a valid
// user api key to be sent in the header of the request
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', 'UserController@details');
    Route::post('logout', 'UserController@logout');
});

// Users:
Route::get('users', 'UserController@index');
Route::post('users/isStudent', 'UserController@isStudent'); // Check if user_id is a student

// Students (note: {student} means student_id):
Route::get('students', 'StudentController@index'); // Get all students
Route::post('students','StudentController@store'); // Create a student

Route::get('students/{student}', 'StudentController@show'); // Get student

Route::get('students/{student}/tags', 'StudentController@tags'); // Get a student's tags
Route::get('students/{student}/skills', 'StudentController@skills'); // Get student's skills
Route::get('students/{student}/labs', 'StudentController@labs'); // Get student's skills

Route::put('students/{student}','StudentController@update'); // Update a student
Route::delete('students/{student}', 'StudentController@delete'); // Delete a student

//Faculty:
Route::get('faculties', 'FacultyController@index'); // Get all faculty
Route::get('faculties/{faculty}', 'FacultyController@show');
Route::post('faculties', 'FacultyController@store');
Route::put('faculties/{faculty}', 'FacultyController@update');

Route::get('faculties/{faculty}/labs', 'FacultyController@getLabs');

// Skills:
Route::get('skills', 'SkillController@index');
Route::get('skills/{skill}', 'SkillController@show');
//Route::post('skills', 'SkillController@store');
//Route::put('skills/{skill}', 'SkillController@update');

// Tags
Route::get('tags', 'SkillController@index');
Route::get('tags/{tag}', 'SkillController@show');