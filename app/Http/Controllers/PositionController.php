<?php

namespace App\Http\Controllers;

use App\Position;
use Illuminate\Http\Request;
use App\Application;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $positions = Position::all();
        return $this->outputJSON($positions, 'Lab Positions retrieved');
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
        $position = new Position($input);
        $position->save();

        return $this->outputJSON($position, 'Lab Position created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function show(Position $position)
    {
        return $this->outputJSON($position, 'Lab Position retrieved');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Position $position)
    {
        $input = $request->all();
        $input = array_filter($input);
        $position->update($input);
        $position->save();
        return $this->outputJSON($position, 'Lab Position updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function destroy(Position $position)
    {
        $position->delete();
        return $this->outputJSON(null, 'Lab Position deleted');
    }

    public function applications(Position $position) {
        $applications = $position->applications()->get();
        return $this->outputJSON($applications,"Applications related to position retrieved");
    }

    public function add_application(Position $position, Request $request) {
        $input = $request->all();
        $application = Application::where('id', $input['application_id']);

        if ($application == null) {
            return $this->outputJSON(null,"Error: Invalid application_id");
        }

        $position->application()->save($application);
        return $this->outputJSON($application,"Application associated to position " . $position->title);
    }

    public function remove_application(Position $position, Request $request) {
        $input = $request->all();
        $application = Application::where('id', $input['application_id']);

        if ($application == null) {
            return $this->outputJSON(null,"Error: Invalid application_id");
        }

        $application->position()->dissociate();
        return $this->outputJSON($application,"Application dissociated from position " . $position->title);
    }
}
