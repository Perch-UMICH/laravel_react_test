<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use App\User;

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
        return response()->json($students);
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
        $student = new Student([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'major' => $request->get('major'),
            'year' => $request->get('year'),
            'gpa' => $request->get('gpa')
        ]);
        $student->save();
        return response()->json($student, 201);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        return response()->json($student);
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
        $student->first_name = $request->get('first_name');
        $student->last_name = $request->get('last_name');
        $student->major = $request->get('major');
        $student->gpa = $request->get('gpa');
        $student->year = $request->get('year');
        $student->save();

        return response()->json($student, 200);
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

        return response()->json(null, 204);
    }

    // Get student skills of a student, based on student_id
    public function skills(Request $request) {
        $input = $request->all();
        $student_id = $input['student_id'];
        $student = Student::where('id', $student_id)->first();
        $skills = $student->skills()->wherePivot('student_id', $student_id)->get();
        return $this->outputJSON($skills,"Skills retrieved");
    }

    // Get student tags of a student, based on student_id
    public function tags(Request $request) {
        $input = $request->all();
        $student_id = $input['student_id'];
        $student = Student::where('id', $student_id)->first();
        $tags = $student->tags()->wherePivot('student_id', $student_id)->get();
        return $this->outputJSON($tags,"Tags retrieved");
    }

    // Get Student based on student_id
    public function getStudent(Request $request)
    {
        $input = $request->all();
        $user_id = $input['student_id'];
        $student = Student::where('id', $user_id)->first();
        return $this->outputJSON($student,"Student retrieved");
    }
}
