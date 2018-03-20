<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use App\User;
use App\Skill;
use App\Tag;
use App\Lab;

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
            $student_data[$student->id] = ['data' => $student, 'skills' => $skills, 'tags' => $tags];
        }
        return $this->outputJSON($student_data, 'Students retrieved');
    }


    // Get Student based on student_id
    public function show(Student $student)
    {
//        $input = $request->all();
//        $student_id = $input['student_id'];
//        $student = Student::where('id', $student_id)->first();
        $skills = $student->skills()->wherePivot('student_id', $student->id)->get();
        $tags = $student->tags()->wherePivot('student_id', $student->id)->get();
        $student_data = ['data' => $student, 'skills' => $skills, 'tags' => $tags];
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
        $student = Student::where('user_id', $input['user_id']);
        if ($student->exists()) {
            return $this->outputJSON($student, 'Error: this user already has a student profile');
        }
        $student = new Student([
            'user_id' => $request->get('user_id'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'major' => $request->get('major'),
            'year' => $request->get('year'),
            'gpa' => $request->get('gpa'),
            'email' => $request->get('email'),
            'bio' => $request->get('bio')
        ]);
        $student->save();
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
//        if ($request->has('first_name')) {
//            $student->first_name = $request->get('first_name');
//        }
//        if ($request->has('last_name')) {
//            $student->last_name = $request->get('last_name');
//        }
//        if ($request->has('major')) {
//            $student->major = $request->get('major');
//        }
//        if ($request->has('gpa')) {
//            $student->gpa = $request->get('gpa');
//        }
//        if ($request->has('year')) {
//            $student->year = $request->get('year');
//        }
//        if ($request->has('email')) {
//            $student->email = $request->get('email');
//        }
//        if ($request->has('bio')) {
//            $student->bio = $request->get('bio');
//        }

        $student->update($request->all());
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

    // Get student skills of a student, based on student_id
    public function skills(Student $student) {
//        $input = $request->all();
//        $student_id = $input['student_id'];
//        $student = Student::where('id', $student_id)->first();
        $skills = $student->skills()->wherePivot('student_id', $student->id)->get();
        return $this->outputJSON($skills,"Skills retrieved");
    }

    // Get student tags of a student, based on student_id
    public function tags(Student $student) {
//        $input = $request->all();
//        $student_id = $input['student_id'];
//        $student = Student::where('id', $student_id)->first();
        $tags = $student->tags()->wherePivot('student_id', $student->id)->get();
        return $this->outputJSON($tags,"Tags retrieved");
    }

    // Get favorited labs of student, based on student_id
    public function labs(Student $student) {
//        $input = $request->all();
//        $student_id = $input['student_id'];
//        $student = Student::where('id', $student_id)->first();
        $labs = $student->labs()->wherePivot('student_id', $student->id)->get();
        return $this->outputJSON($labs,"Labs retrieved");
    }

    public function add_skill(Request $request, Student $student) {
        $input = $request->all();
        $student->skills()->attach([$input['skill_id']]);
    }

    public function add_tag(Request $request, Student $student) {
        $input = $request->all();
        $student->tags()->attach([$input['tag_id']]);
    }

    public function add_lab(Request $request, Student $student) {
        $input = $request->all();
        $student->labs()->attach([$input['lab_id']]);
    }

    public function remove_skill(Request $request, Student $student) {
        $input = $request->all();
        $student->skills()->detach([$input['skill_id']]);
    }

    public function remove_tag(Request $request, Student $student) {
        $input = $request->all();
        $student->tags()->detach([$input['tag_id']]);
    }

    public function remove_lab(Request $request, Student $student) {
        $input = $request->all();
        $student->labs()->detach([$input['lab_id']]);
    }

}
