<?php

namespace App\Http\Controllers;

use App\Position;
use App\Application;
use App\ApplicationResponse;
use App\AppQuestionResponse;
use App\Student;
use Illuminate\Http\Request;
use App\User;
use App\Skill;
use App\Tag;
use App\Lab;

use Illuminate\Database\Eloquent\ModelNotFoundException;


class StudentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //$this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::all();

        $student_data = [];

        foreach( $students as $student ) {
            $skills = $student->skills()->wherePivot('student_id', $student->id)->get();
            $tags = $student->tags()->wherePivot('student_id', $student->id)->get();
            $school_courses = $student->school_courses()->wherePivot('student_id', $student->id)->get();
            $experiences = $student->experiences();
            $student_data[$student->id] = ['data' => $student, 'skills' => $skills,
                'tags' => $tags, 'school_courses' => $school_courses,
                'experiences' => $experiences];
        }
        return $this->outputJSON($student_data, 'Students retrieved');
    }


    // Get Student based on student_id
    public function show(Student $student)
    {
        $skills = $student->skills()->wherePivot('student_id', $student->id)->get();
        $tags = $student->tags()->wherePivot('student_id', $student->id)->get();
        $school_courses = $student->school_courses()->wherePivot('student_id', $student->id)->get();
        $experiences = $student->experiences();
        $student_data = ['data' => $student, 'skills' => $skills,
            'tags' => $tags, 'school_courses' => $school_courses,
            'experiences' => $experiences];
        return $this->outputJSON($student_data,"Student retrieved");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input = array_filter($input);

        $student = Student::where('user_id', $input['user_id'])->count();
        if ($student > 0) {
            $student = Student::where('user_id', $input['user_id'])->get();
            return $this->outputJSON($student, 'Error: this user already has a student profile');
        }
        $user = User::find($input['user_id']);
        if ($user == null) {
            return $this->outputJSON(null, 'Error: user_id is invalid');
        }

        $student = new Student($input);
        $user->is_student = true;
        $user->student()->save($student);
        $user->save();

        return $this->outputJSON($student, 'Student profile created');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {

        $input = $request->all();
        $input = array_filter($input);
        $student->update($input);
        $student->save();

        return $this->outputJSON($student, 'Student profile updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return $this->outputJSON(null, 'Student profile deleted');
    }

    // SKILLS

    // Get student skills of a student, based on student_id
    public function skills(Student $student) {

        $skills = $student->skills()->wherePivot('student_id', $student->id)->get();
        return $this->outputJSON($skills,"Skills retrieved");
    }

    public function add_skill(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['skill_ids'];
        $student->skills()->syncWithoutDetaching($ids);
        return $this->outputJSON(null,"Added skills");
    }

    public function sync_skills(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['skill_ids'];
        $student->skills()->sync($ids);
        return $this->outputJSON(null,"Synced skills");
    }

    public function remove_skill(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['skill_ids'];
        $student->skills()->detach($ids);
        return $this->outputJSON(null,"Removed skills");
    }

    // TAGS

    // Get student tags of a student, based on student_id
    public function tags(Student $student) {
        $tags = $student->tags()->wherePivot('student_id', $student->id)->get();
        return $this->outputJSON($tags,"Tags retrieved");
    }

    public function add_tag(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['tag_ids'];
        $student->tags()->syncWithoutDetaching($ids);
        return $this->outputJSON(null,"Added tags");
    }

    public function sync_tags(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['tag_ids'];
        $student->tags()->sync($ids);
        return $this->outputJSON(null,"Synced tags");
    }

    public function remove_tag(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['tag_ids'];
        $student->tags()->detach($ids);
        return $this->outputJSON(null,"Removed tags");
    }


//    // Get favorited labs of student, based on student_id
//    public function labs(Student $student) {
//        $labs = $student->labs()->wherePivot('student_id', $student->id)->get();
//        return $this->outputJSON($labs,"Labs retrieved");
//    }

    // COURSES

    public function school_courses(Student $student) {
        $courses = $student->school_courses()->wherePivot('student_id', $student->id)->get();
        return $this->outputJSON($courses,"School courses retrieved");
    }

    public function add_school_courses(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['course_ids'];
        $student->school_courses()->sync($ids);
        return $this->outputJSON(null,"Added courses");
    }

    public function remove_school_courses(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['course_ids'];
        $student->school_courses()->detach($ids);
        return $this->outputJSON(null,"Removed courses");
    }

    // SEARCHED LABS

//    public function sync_labs(Request $request, Student $student) {
//        $input = $request->all();
//        $ids = $input['lab_ids'];
//        $student->labs()->sync($ids);
//        return $this->outputJSON(null,"Synced labs");
//    }

//    public function add_lab(Request $request, Student $student) {
//        $input = $request->all();
//        $ids = $input['lab_ids'];
//        $student->labs()->syncWithoutDetaching($ids);
//        return $this->outputJSON(null,"Added labs");
//    }

//    public function remove_lab(Request $request, Student $student)
//    {
//        $input = $request->all();
//        $ids = $input['lab_ids'];
//        $student->labs()->detach($ids);
//        return $this->outputJSON(null, "Removed labs");
//    }

    // LAB MEMBERSHIP

    // TODO

    // APP RESPONSES

    public function app_responses(Student $student) {
        $responses = $student->app_responses;
        return $this->outputJSON($responses,"App responses retrieved");
    }

    public function create_app_response(Request $request, Student $student) {
        $input = $request->all();
        $position = Position::where('id', $input['position_id'])->first();    // Position this is responding to
        $response_strings = $input['answers'];                              // Response strings

        if (!$position) return $this->outputJSON(null, 'Error: Invalid position_id');
        $application = $position->application;

        // Create new response, and associate with application and student
        $response = new ApplicationResponse();
        $response->sent = false;
        $response->save();
        $application->responses()->save($response);
        $student->responses()->save($response);

        // Get application questions
        $questions = $application->questions;

        $count = 0;
        foreach ($questions as $q) {
            $response_string = $response_strings[$count];
            $question_response = new AppQuestionResponse();
            $question_response->response = $response_string;
            $question_response->save();

            // Associate with corresponding AppQuestion and AppResponse
            $q->answers()->save($question_response);
            $response->answers()->save($question_response);
            $count++;
        }

        return $this->outputJSON(['base' => $response, 'answers' => $response->answers], 'Response created for position ' . $position->name);
    }

    public function update_app_response(Request $request, Student $student) {
        $input = $request->all();
        $response_strings = $input['answers'];   // Updated response strings
        $applicationResponse = ApplicationResponse::find($input['application_response_id']);

        if (!$applicationResponse) return $this->outputJSON(null, 'Error: application_response_id is invalid');
        if ($applicationResponse->student->id != $student->id)  return $this->outputJSON(null, 'Error: student of id ' . $student->id . ' does not own this application response');

        $responses = $applicationResponse->responses;

        $count = 0;
        foreach ($responses as $r) {
            $r->response = $response_strings[$count];
            $r->save();
            $count++;
        }

        return $this->outputJSON(['base' => $applicationResponse, 'responses' => $applicationResponse->responses], 'Response updated');
    }

    public function delete_app_response(Request $request, Student $student) {
        $input = $request->all();
        $applicationResponse = ApplicationResponse::find($input['application_response_id']);

        if (!$applicationResponse) return $this->outputJSON(null, 'Error: application_response_id is invalid');
        if ($applicationResponse->student->id != $student->id)  return $this->outputJSON(null, 'Error: student of id ' . $student->id . ' does not own this application response');

        return $this->outputJSON(null, 'Response deleted');
    }

    public function submit_app_response(Request $request, Student $student) {
        $input = $request->all();
        $applicationResponse = ApplicationResponse::find($input['application_response_id']);

        if (!$applicationResponse) return $this->outputJSON(null, 'Error: application_response_id is invalid');
        if ($applicationResponse->student->id != $student->id) return $this->outputJSON(null, 'Error: student of id ' . $student->id . ' does not own this application response');

        $applicationResponse->sent = true;

        return $this->outputJSON(null, 'Response submitted to position ' . $applicationResponse->application->position->name);
    }
}
