<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\Faculty;
use App\Lab;

class ProfilepicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $path = $request->file('image')->store('images');
        $type = $request->get('type');
        if ($type == 'student') {
            $student_id = $request->get('id');
            $student = Student::find($student_id);
            if (!$student) {
                return $this->outputJSON(null, 'Error: ID is invalid');
            }
            $student->profilepic_path = $path;
            $student->save();
            return $this->outputJSON($student, 'Added student profile pic');
        }
        else if ($type == 'faculty') {
            $faculty_id = $request->get('id');
            $faculty = Faculty::find($faculty_id);
            if (!$faculty) {
                return $this->outputJSON(null, 'Error: ID is invalid');
            }
            $faculty->profilepic_path = $path;
            $faculty->save();
            return $this->outputJSON($faculty, 'Added faculty profile pic');
        }
        else if ($type == 'lab') {
            $lab_id = $request->get('id');
            $lab = Lab::find($lab_id);
            if (!$lab) {
                return $this->outputJSON(null, 'Error: ID is invalid');
            }
            $lab->labpic_path = $path;
            $lab->save();
            return $this->outputJSON($lab, 'Added lab profile pic');
        }
        else {
            return $this->outputJSON(null, 'Error: type is invalid');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
