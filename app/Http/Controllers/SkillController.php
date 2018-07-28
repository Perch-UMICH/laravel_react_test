<?php

namespace App\Http\Controllers;

use App\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $skills = Skill::all();
        return $this->outputJSON($skills, 'Retrieved skills');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $skill = Skill::where('name', $request['name']);
        if ($skill) {
            return $this->outputJSON($skill, 'Error: skill of this name already exists');
        }

        $skill = new Skill([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
        ]);
        $skill->save();
        return $this->outputJSON($skill, 'Skill definition created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Skill  $skill
     * @return \Illuminate\Http\Response
     */
    public function show(Skill $skill)
    {
        return $this->outputJSON($skill, 'Retrieved skill');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Skill  $skill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Skill $skill)
    {
        $skill->name = $request->get('name');
        $skill->description = $request->get('description');
        $skill->save();

        return $this->outputJSON($skill, 'Skill definition updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Skill  $skill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Skill $skill)
    {
        $skill->delete();
        return $this->outputJSON(null, 'Skill definition deleted');
    }

    // Searches for skills that are a close match to the requested name
    public function search_matching_skills(Request $request)
    {
        $input = $request->all();
        $query = $input['query'];
        $skills = Skill::all()->pluck('name')->toArray();
        $selected = $this->exact_match($query, $skills);

        return $this->outputJSON($selected, 'Returned closest matching skills');
    }

}
