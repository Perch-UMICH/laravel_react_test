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
        $input = array_filter($input);

        $student = Student::where('user_id', $input['user_id']);
        if ($student != null) {
            return $this->outputJSON($student->get(), 'Error: this user already has a student profile');
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

    // Get student skills of a student, based on student_id
    public function skills(Student $student) {

        $skills = $student->skills()->wherePivot('student_id', $student->id)->get();
        return $this->outputJSON($skills,"Skills retrieved");
    }

    // Get student tags of a student, based on student_id
    public function tags(Student $student) {
        $tags = $student->tags()->wherePivot('student_id', $student->id)->get();
        return $this->outputJSON($tags,"Tags retrieved");
    }

    // Get favorited labs of student, based on student_id
    public function labs(Student $student) {
        $labs = $student->labs()->wherePivot('student_id', $student->id)->get();
        return $this->outputJSON($labs,"Labs retrieved");
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

    public function add_lab(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['lab_ids'];
        $student->labs()->syncWithoutDetaching($ids);
        return $this->outputJSON(null,"Added labs");
    }

    public function sync_labs(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['lab_ids'];
        $student->labs()->sync($ids);
        return $this->outputJSON(null,"Synced labs");
    }

    public function remove_skill(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['skill_ids'];
        $student->skills()->detach($ids);
        return $this->outputJSON(null,"Removed skills");
    }

    public function remove_tag(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['tag_ids'];
        $student->tags()->detach($ids);
        return $this->outputJSON(null,"Removed tags");
    }

    public function remove_lab(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['lab_ids'];
        $student->labs()->detach($ids);
        return $this->outputJSON(null,"Removed labs");
    }

}
