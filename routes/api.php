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
Route::put('students/{student}','StudentController@update'); // Update a student
Route::delete('students/{student}', 'StudentController@destroy'); // Delete a student

Route::get('students/{student}/tags', 'StudentController@tags'); // Get a student's tags
Route::post('students/{student}/tags', 'StudentController@add_tag');
Route::delete('students/{student}/tags', 'StudentController@remove_tag');


Route::get('students/{student}/skills', 'StudentController@skills'); // Get student's skills
Route::post('students/{student}/skills', 'StudentController@add_skill');
Route::delete('students/{student}/skills', 'StudentController@remove_skill');


Route::get('students/{student}/labs', 'StudentController@labs'); // Get student's skills
Route::post('students/{student}/labs', 'StudentController@add_lab');
Route::delete('students/{student}/labs', 'StudentController@remove_skill');

//Faculty:
Route::get('faculties', 'FacultyController@index'); // Get all faculty
Route::get('faculties/{faculty}', 'FacultyController@show');
Route::post('faculties', 'FacultyController@store');
Route::put('faculties/{faculty}', 'FacultyController@update');
Route::delete('faculties/{faculty}', 'FacultyController@destroy');

Route::get('faculties/{faculty}/labs', 'FacultyController@labs');
Route::post('faculties/{faculty}/labs', 'FacultyController@add_lab');
Route::delete('faculties/{faculty}/labs', 'FacultyController@remove_lab');

// Labs
Route::get('labs', 'LabController@index');
Route::get('labs/{lab}', 'LabController@show');
Route::post('labs', 'LabController@store');
Route::put('labs/{lab}', 'LabController@update');
Route::delete('labs/{lab}', 'LabController@destroy');

Route::get('labs/{lab}/students', 'LabController@students');
Route::post('labs/{lab}/students', 'LabController@add_students');
Route::delete('labs/{lab}/students', 'LabController@remove_student');

Route::get('labs/{lab}/faculties', 'LabController@faculties');
Route::post('labs/{lab}/faculties', 'LabController@add_faculty');
Route::delete('labs/{lab}/faculties', 'LabController@remove_faculty');

Route::get('labs/{lab}/skills', 'LabController@skills');
Route::post('labs/{lab}/skills', 'LabController@add_skill');
Route::delete('labs/{lab}/skills', 'LabController@remove_skill');


Route::get('labs/{lab}/tags', 'LabController@tags');
Route::post('labs/{lab}/tags', 'LabController@add_tag');
Route::delete('labs/{lab}/tags', 'LabController@remove_tag');

// Skills:
Route::get('skills', 'SkillController@index');
Route::get('skills/{skill}', 'SkillController@show');
//Route::post('skills', 'SkillController@store');
//Route::put('skills/{skill}', 'SkillController@update');

// Tags
Route::get('tags', 'SkillController@index');
Route::get('tags/{tag}', 'SkillController@show');

