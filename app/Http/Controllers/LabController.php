<?php

namespace App\Http\Controllers;

use App\Lab;
use App\Position;
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

        $lab_data = [];

        foreach( $labs as $lab ) {
            $skills = $lab->skills()->wherePivot('lab_id', $lab->id)->get();
            $tags = $lab->tags()->wherePivot('lab_id', $lab->id)->get();
            $positions = $lab->positions()->get();
            $lab_data[$lab->id] = ['data' => $lab, 'skills' => $skills, 'tags' => $tags, 'positions' => $positions];
        }
        return $this->outputJSON($lab_data,"Labs retrieved");
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

        $lab = Lab::where('name', $request['name']);
        if ($lab == null) {
            return $this->outputJSON($lab, 'Error: lab with this name already exists');
        }

        $lab = new Lab($input);
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
        $skills = $lab->skills()->wherePivot('lab_id', $lab->id)->get();
        $tags = $lab->tags()->wherePivot('lab_id', $lab->id)->get();

        $lab_data = ['data' => $lab, 'skills' => $skills, 'tags' => $tags];

        return $this->outputJSON($lab_data,"Lab retrieved");
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
        $input = array_filter($input);
        $lab->update($input);
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

    // Lab associations

    // Retrieve

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

    public function preferences(Lab $lab) {
        $preferences = $lab->preferences()->wherePivot('lab_id', $lab->id)->get();
        return $this->outputJSON($preferences,"Preferences from lab retrieved");
    }

    public function positions(Lab $lab) {
        $positions = $lab->positions()->get();
        return $this->outputJSON($positions,"Positions from lab retrieved");
    }

    // Add

    public function add_skill(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['skill_ids'];
        $lab->skills()->syncWithoutDetaching($ids);
        return $this->outputJSON(null,"Added skills");
    }

    public function add_tag(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['tag_ids'];
        $lab->tags()->syncWithoutDetaching($ids);
        return $this->outputJSON(null,"Added tags");
    }

    public function add_student(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['student_ids'];
        $lab->students()->syncWithoutDetaching($ids);
        return $this->outputJSON(null,"Added students");
    }

    public function add_faculty(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['faculty_ids'];
        $lab->faculties()->syncWithoutDetaching($ids);
        return $this->outputJSON(null,"Added faculty");
    }

    public function add_preference(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['preference_ids'];
        $lab->preferences()->syncWithoutDetaching($ids);
        return $this->outputJSON(null,"Added preferences");
    }

    public function create_and_add_position(Request $request, Lab $lab) {
        $input = $request->all();
        $position = new Position($input);
        $position->save();

        $lab->positions()->save($position);
        return $this->outputJSON($position,"Created position " . $position->title . " and added to lab " . $lab->name);
    }

    // Remove

    public function remove_skill(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['skill_ids'];
        $lab->skills()->detach($ids);
        return $this->outputJSON(null,"Removed skills from lab " . $lab->name);
    }

    public function remove_tag(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['tag_ids'];
        $lab->tags()->detach($ids);
        return $this->outputJSON(null,"Removed tags from lab " . $lab->name);
    }

    public function remove_student(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['student_ids'];
        $lab->students()->detach($ids);
        return $this->outputJSON(null,"Removed students from lab " . $lab->name);
    }

    public function remove_faculty(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['faculty_ids'];
        $lab->faculties()->detach($ids);
        return $this->outputJSON(null,"Removed faculty from lab " . $lab->name);
    }

    public function remove_preference(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['preference_ids'];
        $lab->preferences()->detach($ids);
        return $this->outputJSON(null,"Removed preferences from lab " . $lab->name);
    }

    public function remove_and_delete_positions(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['position_ids'];
        Position::destroy($ids);

        return $this->outputJSON(null,"Removed and deleted positions from lab " . $lab->name);
    }
}
