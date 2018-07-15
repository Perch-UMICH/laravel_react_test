<?php

namespace App\Http\Controllers;

use App\Position;
use Illuminate\Http\Request;
use App\Application;
use App\AppQuestion;
use Illuminate\Support\Collection;

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

}
