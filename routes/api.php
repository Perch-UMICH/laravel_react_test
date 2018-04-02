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

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('login/google', 'Auth\LoginController@redirectToProvider');
Route::get('login/google/callback', 'Auth\LoginController@handleProviderCallback');

// Note: calls to routes protected by auth:api require a valid
// user api key to be sent in the header of the request
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('verify', 'UserController@verify');
    Route::post('logout', 'UserController@logout');
});

// Users:
Route::get('users', 'UserController@index');
Route::post('users/isStudent', 'UserController@isStudent'); // Check if user_id is a student
Route::get('users/{user}', 'UserController@show');
Route::put('users/{user}', 'UserController@update');
Route::delete('users/{user}', 'UserController@delete');

Route::get('users/{user}/student', 'UserController@get_student_profile');
Route::get('users/{user}/faculty', 'UserController@get_faculty_profile');

// Students (note: {student} means student_id):
Route::get('students', 'StudentController@index'); // Get all students
Route::post('students','StudentController@store'); // Create a student
Route::get('students/{student}', 'StudentController@show'); // Get student
Route::put('students/{student}','StudentController@update'); // Update a student
Route::delete('students/{student}', 'StudentController@destroy'); // Delete a student

Route::get('students/{student}/tags', 'StudentController@tags'); // Get a student's tags
Route::post('students/{student}/tags', 'StudentController@add_tag');
Route::post('students/{student}/tags/sync', 'StudentController@sync_tags'); // sync -> delete all and replace with only input
Route::put('students/{student}/tags', 'StudentController@remove_tag');


Route::get('students/{student}/skills', 'StudentController@skills'); // Get student's skills
Route::post('students/{student}/skills', 'StudentController@add_skill');
Route::post('students/{student}/skills/sync', 'StudentController@sync_skills');
Route::put('students/{student}/skills', 'StudentController@remove_skill');


Route::get('students/{student}/labs', 'StudentController@labs');
Route::post('students/{student}/labs', 'StudentController@add_lab');
Route::put('students/{student}/labs', 'StudentController@remove_skill');

//Faculty:
Route::get('faculties', 'FacultyController@index'); // Get all faculty
Route::get('faculties/{faculty}', 'FacultyController@show');
Route::post('faculties', 'FacultyController@store');
Route::put('faculties/{faculty}', 'FacultyController@update');
Route::delete('faculties/{faculty}', 'FacultyController@destroy');

Route::get('faculties/{faculty}/labs', 'FacultyController@labs');
Route::post('faculties/{faculty}/labs', 'FacultyController@add_lab');
Route::put('faculties/{faculty}/labs', 'FacultyController@remove_lab');

// LABS

// New getters
Route::post('labs/all', 'LabController@get_all_labs');
Route::post('labs/{lab}', 'LabController@get_lab');

Route::get('labs', 'LabController@index'); // Get all faculty
Route::get('labs/{lab}', 'LabController@show');

Route::post('labs', 'LabController@store');
Route::put('labs/{lab}', 'LabController@update');
Route::delete('labs/{lab}', 'LabController@destroy');

Route::get('labs/{lab}/students', 'LabController@students');
Route::post('labs/{lab}/students', 'LabController@add_students');
Route::put('labs/{lab}/students', 'LabController@remove_student');

Route::get('labs/{lab}/faculties', 'LabController@faculties');
Route::post('labs/{lab}/faculties', 'LabController@add_faculty');
Route::put('labs/{lab}/faculties', 'LabController@remove_faculty');

Route::get('labs/{lab}/skills', 'LabController@skills');
Route::post('labs/{lab}/skills', 'LabController@add_skill');
Route::post('labs/{lab}/skills/sync', 'LabController@sync_skills');
Route::put('labs/{lab}/skills', 'LabController@remove_skill');

Route::get('labs/{lab}/tags', 'LabController@tags');
Route::post('labs/{lab}/tags', 'LabController@add_tag');
Route::post('labs/{lab}/tags/sync', 'LabController@sync_tags');
Route::put('labs/{lab}/tags', 'LabController@remove_tag');

Route::get('labs/{lab}/preferences', 'LabController@preferences');
Route::post('labs/{lab}/preferences', 'LabController@add_preference');
Route::put('labs/{lab}/preferences', 'LabController@remove_preference');

// Positions
Route::get('labs/{lab}/positions', 'LabController@positions');
Route::post('labs/{lab}/positions', 'LabController@create_position');
Route::put('labs/{lab}/positions', 'LabController@delete_positions');

Route::get('positions/{position}', 'PositionController@show');
Route::put('positions/{position}','PositionController@update');

// Applications
Route::get('labs/{lab}/applications', 'LabController@applications');
Route::post('labs/{lab}/applications', 'LabController@create_application');
Route::put('applications/{application}','ApplicationController@update');
Route::put('labs/{lab}/applications', 'LabController@delete_applications');

Route::get('applications/{application}/questions', 'ApplicationController@get_app_questions');
Route::post('applications/{application}/questions/create', 'ApplicationController@create_question');
Route::post('applications/{application}/questions/associate', 'ApplicationController@add_existing_question');
Route::put('questions/{question}', 'ApplicationController@update_question');
Route::post('applications/{application}/questions/dissociate', 'ApplicationController@remove_questions');
Route::post('applications/{application}/questions/delete', 'ApplicationController@delete_questions');

Route::get('questions', 'ApplicationController@get_public_questions');

// Skills:
Route::get('skills', 'SkillController@index');
Route::get('skills/{skill}', 'SkillController@show');
//Route::post('skills', 'SkillController@store');
//Route::put('skills/{skill}', 'SkillController@update');

// Tags
Route::get('tags', 'TagController@index');
Route::get('tags/{tag}', 'TagController@show');



// Profile pics
Route::post('pics', 'ProfilepicController@store');

// Feedback
Route::get('feedback', 'FeedbackController@index');
Route::post('feedback', 'FeedbackController@store');

// Search
Route::post('search', 'SearchController@get_search_data');

