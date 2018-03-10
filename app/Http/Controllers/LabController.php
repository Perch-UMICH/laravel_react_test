<?php

namespace App\Http\Controllers;

use App\Lab;
use Illuminate\Http\Request;

class LabController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $labs = Lab::all();
        return $this->outputJSON($labs,"Labs retrieved");
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
        $lab = Lab::where('name', $request['name']);
        if ($lab == null) {
            return $this->outputJSON($lab, 'Error: lab with this name already exists');
        }
        $lab = new Lab([
            'name' => $request->get('name'),
            'department' => $request->get('department'),
            'location' => $request->get('location'),
            'description' => $request->get('description'),
            'publications' => $request->get('publications'),
            'url' => $request->get('url'),
            'gpa' => $request->get('gpa'),
            'weeklyCommitment' => $request->get('weeklyCommitment')
        ]);
        $lab->save();
        return $this->outputJSON($lab, 'Lab page created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lab  $lab
     * @return \Illuminate\Http\Response
     */
    public function show(Lab $lab)
    {
        return $this->outputJSON($lab,"Lab retrieved");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lab  $lab
     * @return \Illuminate\Http\Response
     */
    public function edit(Lab $lab)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lab  $lab
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lab $lab)
    {
        $input = $request->all();
        $lab->name = $input['name'];
        $lab->department = $input['department'];
        $lab->location = $input['location'];
        $lab->description = $input['description'];
        $lab->publications = $input['publications'];
        $lab->url = $input['url'];
        $lab->gpa = $input['gpa'];
        $lab->weeklyCommitment = $input['weeklyCommitment'];

        $lab->save();

        return $this->outputJSON($lab, 'Lab page updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lab  $lab
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lab $lab)
    {
        $lab->delete();

        return $this->outputJSON(null, 'Lab page deleted');
    }

    public function students(Lab $lab) {
        $students = $lab->students()->wherePivot('lab_id', $lab->id)->get();
        return $this->outputJSON($students,"Students from lab retrieved");
    }

    public function faculties(Lab $lab) {
        $faculties = $lab->faculties()->wherePivot('lab_id', $lab->id)->get();
        return $this->outputJSON($faculties,"Faculties from lab retrieved");
    }

    public function skills(Lab $lab) {
        $skills = $lab->skills()->wherePivot('lab_id', $lab->id)->get();
        return $this->outputJSON($skills,"Skills from lab retrieved");
    }

    public function tags(Lab $lab) {
        $tags = $lab->tags()->wherePivot('lab_id', $lab->id)->get();
        return $this->outputJSON($tags,"Tags from lab retrieved");
    }

    public function add_skill(Request $request, Lab $lab) {
        $input = $request->all();
        $lab->skills()->attach([$input['skill_id']]);
    }

    public function add_tag(Request $request, Lab $lab) {
        $input = $request->all();
        $lab->tags()->attach([$input['tag_id']]);
    }

    public function add_student(Request $request, Lab $lab) {
        $input = $request->all();
        $lab->students()->attach([$input['student_id']]);
    }

    public function add_faculty(Request $request, Lab $lab) {
        $input = $request->all();
        $lab->faculties()->attach([$input['faculty_id']]);
    }

    public function remove_skill(Request $request, Lab $lab) {
        $input = $request->all();
        $lab->skills()->detach([$input['skill_id']]);
    }

    public function remove_tag(Request $request, Lab $lab) {
        $input = $request->all();
        $lab->tags()->detach([$input['tag_id']]);
    }

    public function remove_student(Request $request, Lab $lab) {
        $input = $request->all();
        $lab->students()->detach([$input['student_id']]);
    }

    public function remove_faculty(Request $request, Lab $lab) {
        $input = $request->all();
        $lab->faculties()->detach([$input['faculty_id']]);
    }
}
