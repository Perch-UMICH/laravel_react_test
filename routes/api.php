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


// ACCOUNTS //

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

Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLink');
//email [your-email-address]
//password [new-password]
//password_confirmation [retype-new-password]
//token [the-token-you-get-from-previous-one]
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

//Route::post('password/request', 'Auth\ResetPass');

// USERS //
Route::get('users', 'UserController@index');
Route::post('users/isStudent', 'UserController@isStudent'); // Check if user_id is a student
Route::get('users/{user}', 'UserController@show');
Route::put('users/{user}', 'UserController@update');
Route::delete('users/{user}', 'UserController@delete');

Route::get('users/{user}/student', 'UserController@get_student_profile');
Route::get('users/{user}/faculty', 'UserController@get_faculty_profile');
Route::get('users/{user}/labs', 'UserController@get_labs');

// STUDENTS (note: {student} means student_id): //
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

Route::get('students/{student}/courses/school', 'StudentController@school_courses');
Route::post('students/{student}/courses/school', 'StudentController@add_school_courses');
Route::put('students/{student}/courses/school', 'StudentController@remove_school_courses');

//Faculty:
Route::get('faculties', 'FacultyController@index'); // Get all faculty
Route::get('faculties/{faculty}', 'FacultyController@show');
Route::post('faculties', 'FacultyController@store');
Route::put('faculties/{faculty}', 'FacultyController@update');
Route::delete('faculties/{faculty}', 'FacultyController@destroy');


// LABS //

// New getters
Route::post('labs/all', 'LabController@get_all_labs');
Route::post('labs/{lab}', 'LabController@get_lab');

Route::get('labs', 'LabController@index'); // Get all faculty
Route::get('labs/{lab}', 'LabController@show');

Route::post('labs', 'LabController@store');
Route::put('labs/{lab}', 'LabController@update');
Route::delete('labs/{lab}', 'LabController@destroy');


// Lab Members
Route::get('labs/{lab}/members', 'LabController@members');
Route::post('labs/{lab}/members', 'LabController@add_members');
Route::put('labs/{lab}/members', 'LabController@remove_members');

// Lab skills/tags

Route::get('labs/{lab}/skills', 'LabController@skills');
Route::post('labs/{lab}/skills', 'LabController@add_skill');
Route::post('labs/{lab}/skills/sync', 'LabController@sync_skills');
Route::put('labs/{lab}/skills', 'LabController@remove_skill');

Route::get('labs/{lab}/tags', 'LabController@tags');
Route::post('labs/{lab}/tags', 'LabController@add_tag');
Route::post('labs/{lab}/tags/sync', 'LabController@sync_tags');
Route::put('labs/{lab}/tags', 'LabController@remove_tag');

Route::get('preferences', 'LabPreferenceController@index');
Route::get('labs/{lab}/preferences', 'LabController@preferences');
Route::post('labs/{lab}/preferences', 'LabController@add_preference');
Route::put('labs/{lab}/preferences', 'LabController@remove_preference');

// POSITIONS //
Route::get('labs/{lab}/positions', 'LabController@positions'); // get all from lab
Route::post('labs/{lab}/positions', 'LabController@create_position'); // create for lab
Route::put('labs/{lab}/positions', 'LabController@delete_positions'); // delete (also deletes application)

Route::get('positions/{position}', 'PositionController@show'); // get individual
Route::put('positions/{position}','PositionController@update'); // update

// Applications
Route::get('positions/{position}/application', 'PositionController@application'); // get from position
Route::post('positions/{position}/application', 'PositionController@create_application'); // create for position
Route::put('positions/{position}/application', 'PositionController@update_application'); // update (app and questions)

Route::get('questions', 'ApplicationController@public_questions'); // get public questions

// Response
Route::get('students/{student}/responses', 'StudentController@app_responses'); // Get student responses
Route::post('students/{student}/responses', 'StudentController@create_app_response'); // Create a response
Route::post('students/{student}/responses/update', 'StudentController@update_app_response'); // Update a response
Route::post('students/{student}/responses/submit', 'StudentController@submit_app_response'); // Submit a response
Route::post('students/{student}/responses/delete', 'StudentController@delete_app_response'); // Delete a response

Route::get('positions/{position}/application/responses', 'PositionController@app_responses'); // Get all responses to an application


// METADATA //

// Skills:
Route::get('skills', 'SkillController@index');
Route::get('skills/{skill}', 'SkillController@show');
//Route::post('skills', 'SkillController@store');
//Route::put('skills/{skill}', 'SkillController@update');

// Tags
Route::get('tags', 'TagController@index');
Route::get('tags/{tag}', 'TagController@show');

// School Courses
Route::get('courses/school', 'SchoolCourseController@index');

// MISC //

// Profile pics
Route::post('pics', 'ProfilepicController@store');

// Feedback
Route::get('feedback', 'FeedbackController@index');
Route::post('feedback', 'FeedbackController@store');

// Search
Route::post('search', 'SearchController@get_search_data');

