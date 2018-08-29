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
Route::post('register', 'UserController@register');

// User Login (Access Token Request):
Route::post('login', 'UserController@login');

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('login/google', 'Auth\LoginController@redirectToProvider');
Route::get('login/google/callback', 'Auth\LoginController@handleProviderCallback');

// Note: calls to routes protected by auth:api require a valid
// user api key to be sent in the header of the request

// MUST BE LOGGED IN
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('verify', 'UserController@verify');
    Route::post('logout', 'UserController@logout');

    // User edits
    Route::put('users/{user}', 'UserController@update');
    Route::delete('users/{user}', 'UserController@delete');

    // Faculty edits
    Route::post('faculties', 'FacultyController@store');
    Route::put('faculties/{faculty}', 'FacultyController@update');
    Route::delete('faculties/{faculty}', 'FacultyController@destroy');

    // Lab creation
    Route::post('labs', 'LabController@store');

    // Search
    Route::get('search_data', 'SearchController@get_search_data_urop');
    Route::post('search', 'SearchController@search_urop');
    Route::post('search/results', 'SearchController@retrieve_search_data');

    Route::post('skills/match','SkillController@search_matching_skills');
    Route::post('tags/match','TagController@search_matching_tags');

    // File upload
    Route::post('users/{user}/profile_pic', 'FileController@add_profile_pic_to_user');
    Route::post('users/{user}/resume', 'FileController@add_resume_to_user');
    Route::get('users/{user}/profile_pic', 'FileController@get_user_profile_pic');
    Route::get('users/{user}/resume', 'FileController@get_user_resume');

    // Lab edits
    // MUST BE LOGGED IN + BE LAB OWNER
    Route::group(['middleware' => 'lab_owner'], function() {
        Route::put('labs/{lab}', 'LabController@update');
        Route::delete('labs/{lab}', 'LabController@destroy');

        Route::post('labs/{lab}/members', 'LabController@add_members');
        Route::put('labs/{lab}/members', 'LabController@remove_members');

        Route::post('labs/{lab}/skills', 'LabController@add_skill');
        Route::post('labs/{lab}/skills/sync', 'LabController@sync_skills');
        Route::put('labs/{lab}/skills', 'LabController@remove_skill');

        Route::post('labs/{lab}/tags', 'LabController@add_tag');
        Route::post('labs/{lab}/tags/sync', 'LabController@sync_tags');
        Route::put('labs/{lab}/tags', 'LabController@remove_tag');

        Route::get('labs/{lab}/preferences', 'LabController@preferences');
        Route::post('labs/{lab}/preferences', 'LabController@add_preference');
        Route::put('labs/{lab}/preferences', 'LabController@remove_preference');

        Route::post('labs/{lab}/positions', 'LabController@create_position'); // create for lab
        Route::post('labs/{lab}/positions/update', 'LabController@update_position'); // create for lab
        Route::post('labs/{lab}/positions/delete', 'LabController@delete_positions'); // delete (also deletes application)
        Route::post('labs/{lab}/positions/responses', 'LabController@app_responses'); // Get all responses to an application

        Route::post('labs/{lab}/applications', 'LabController@create_application');
        Route::post('labs/{lab}/applications/update', 'LabController@update_application');
    });

    // Student creation
    Route::post('students','StudentController@store'); // Create a student

    // Student edits
    // MUST BE LOGGED IN + BE A STUDENT PROFILE OWNER
    Route::group(['middleware' => 'student_profile_owner'], function() {
        //Route::get('students/{student}', 'StudentController@show'); // Get student
        Route::put('students/{student}','StudentController@update'); // Update a student
        Route::delete('students/{student}', 'StudentController@destroy'); // Delete a student

        // Skills and Tags
        Route::post('students/{student}/tags', 'StudentController@add_tag');
        Route::post('students/{student}/tags/sync', 'StudentController@sync_tags'); // sync -> delete all and replace with only input
        Route::put('students/{student}/tags', 'StudentController@remove_tag');

        Route::post('students/{student}/skills', 'StudentController@add_skill');
        Route::post('students/{student}/skills/sync', 'StudentController@sync_skills');
        Route::put('students/{student}/skills', 'StudentController@remove_skill');
        //

        // Experiences
        Route::post('students/{student}/work_experiences', 'StudentController@create_and_add_work_experience');
        Route::put('students/{student}/work_experiences', 'StudentController@update_work_experience');
        Route::post('students/{student}/work_experiences/delete', 'StudentController@remove_work_experiences');

        Route::post('students/{student}/edu_experiences', 'EduExperienceController@store');
        Route::put('students/{student}/edu_experiences', 'EduExperienceController@update');
        Route::post('students/{student}/edu_experiences/delete', 'EduExperienceController@delete');
        //

        // Lab list
        Route::post('students/{student}/position_list', 'StudentController@add_to_position_list');
        Route::put('students/{student}/position_list', 'StudentController@remove_from_position_list');

        Route::post('students/{student}/resume', 'StudentController@add_resume');

        Route::get('students/{student}/responses', 'StudentController@app_responses'); // Get student responses
        Route::post('students/{student}/responses', 'StudentController@create_app_response'); // Create a response
        Route::post('students/{student}/responses/update', 'StudentController@update_app_response'); // Update a response
        Route::post('students/{student}/responses/submit', 'StudentController@submit_app_response'); // Submit a response
        Route::post('students/{student}/responses/delete', 'StudentController@delete_app_response'); // Delete a response
    });

    // METADATA //

    Route::post('skills/match','SkillController@search_matching_skills');
    Route::post('tags/match','TagController@search_matching_tags');

    // Skills:
    Route::get('skills', 'SkillController@index');
    Route::get('skills/{skill}', 'SkillController@show');
    Route::post('skills', 'SkillController@store');
    Route::put('skills/{skill}', 'SkillController@update');

    // Tags
    Route::get('tags', 'TagController@index');
    Route::get('tags/{tag}', 'TagController@show');
    Route::post('tags', 'TagController@store');
    Route::put('tags/{tag}', 'TagController@update');


    // MISC //

    // Profile pics (for students, labs, and faculty)
    Route::post('pics', 'ProfilepicController@store');

    // Feedback
    Route::get('feedback', 'FeedbackController@index');
    Route::post('feedback', 'FeedbackController@store');
});


// ALL ROUTES BELOW ARE PUBLIC

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
//Route::put('users/{user}', 'UserController@update');
//Route::delete('users/{user}', 'UserController@delete');

Route::get('users/{user}/student', 'UserController@get_student_profile');
Route::get('users/{user}/faculty', 'UserController@get_faculty_profile');
Route::get('users/{user}/labs', 'UserController@get_labs');

// STUDENTS (note: {student} means student_id): //
Route::get('students', 'StudentController@index'); // Get all students
//Route::post('students','StudentController@store'); // Create a student
Route::get('students/{student}', 'StudentController@show'); // Get student
//Route::put('students/{student}','StudentController@update'); // Update a student
//Route::delete('students/{student}', 'StudentController@destroy'); // Delete a student

// Student tags
Route::get('students/{student}/tags', 'StudentController@tags'); // Get a student's tags
//Route::post('students/{student}/tags', 'StudentController@add_tag');
//Route::post('students/{student}/tags/sync', 'StudentController@sync_tags'); // sync -> delete all and replace with only input
//Route::put('students/{student}/tags', 'StudentController@remove_tag');

// Student skills
Route::get('students/{student}/skills', 'StudentController@skills'); // Get student's skills
//Route::post('students/{student}/skills', 'StudentController@add_skill');
//Route::post('students/{student}/skills/sync', 'StudentController@sync_skills');
//Route::put('students/{student}/skills', 'StudentController@remove_skill');


// Student classes
Route::get('students/{student}/courses/school', 'StudentController@school_courses');
//Route::post('students/{student}/courses/school', 'StudentController@add_school_courses');
//Route::put('students/{student}/courses/school', 'StudentController@remove_school_courses');

// Student resume
//Route::post('students/{student}/resume', 'StudentController@add_resume');

// FACULTY //
Route::get('faculties', 'FacultyController@index'); // Get all faculty
Route::get('faculties/{faculty}', 'FacultyController@show');
//Route::post('faculties', 'FacultyController@store');
//Route::put('faculties/{faculty}', 'FacultyController@update');
//Route::delete('faculties/{faculty}', 'FacultyController@destroy');

// LABS //

// New getters
Route::post('labs/all', 'LabController@get_all_labs');
Route::post('labs/{lab}', 'LabController@get_lab');

Route::get('labs', 'LabController@index');
Route::get('labs/{lab}', 'LabController@show');

//Route::post('labs', 'LabController@store');
//Route::put('labs/{lab}', 'LabController@update');
//Route::delete('labs/{lab}', 'LabController@destroy');


// Lab Members
Route::get('labs/{lab}/members', 'LabController@members');
//Route::post('labs/{lab}/members', 'LabController@add_members');
//Route::put('labs/{lab}/members', 'LabController@remove_members');

// Lab skills/tags

Route::get('labs/{lab}/skills', 'LabController@skills');
//Route::post('labs/{lab}/skills', 'LabController@add_skill');
//Route::post('labs/{lab}/skills/sync', 'LabController@sync_skills');
//Route::put('labs/{lab}/skills', 'LabController@remove_skill');

Route::get('labs/{lab}/tags', 'LabController@tags');
//Route::post('labs/{lab}/tags', 'LabController@add_tag');
//Route::post('labs/{lab}/tags/sync', 'LabController@sync_tags');
//Route::put('labs/{lab}/tags', 'LabController@remove_tag');

Route::get('preferences', 'LabPreferenceController@index');
//Route::get('labs/{lab}/preferences', 'LabController@preferences');
//Route::post('labs/{lab}/preferences', 'LabController@add_preference');
//Route::put('labs/{lab}/preferences', 'LabController@remove_preference');

// POSITIONS //
Route::get('labs/{lab}/positions', 'LabController@positions'); // get all from lab
Route::get('labs/{lab}/position/{position}', 'LabController@position'); // get single from lab
//Route::post('labs/{lab}/positions', 'LabController@create_position'); // create for lab
//Route::post('labs/{lab}/positions/update', 'LabController@update_position'); // create for lab
//Route::post('labs/{lab}/positions/delete', 'LabController@delete_positions'); // delete (also deletes application)


// Applications
//Route::get('positions/{position}/application', 'PositionController@application'); // get from position
//Route::post('labs/{lab}/applications', 'LabController@create_application');
//Route::post('labs/{lab}/applications/update', 'LabController@update_application');
//Route::get('labs/{lab}/positions/responses', 'LabController@app_responses'); // Get all responses to an application


// Responses
//Route::get('students/{student}/responses', 'StudentController@app_responses'); // Get student responses
//Route::post('students/{student}/responses', 'StudentController@create_app_response'); // Create a response
//Route::post('students/{student}/responses/update', 'StudentController@update_app_response'); // Update a response
//Route::post('students/{student}/responses/submit', 'StudentController@submit_app_response'); // Submit a response
//Route::post('students/{student}/responses/delete', 'StudentController@delete_app_response'); // Delete a response





// scale
// position (x,y)
// rotation
// width
// height
