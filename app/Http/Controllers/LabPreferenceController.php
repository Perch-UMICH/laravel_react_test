<?php

namespace App\Http\Controllers;

use App\Lab;
use App\LabPreference;
use Illuminate\Http\Request;

class LabPreferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $preferences = LabPreference::all();
        return $this->outputJSON($preferences, 'Lab Preferences retrieved');
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
        $preference = new LabPreference($input);
        if ($preference->exists) {
            return $this->outputJSON($preference, 'Error: Preference of this type and desc already exists');
        }
        $preference->save();

        return $this->outputJSON($preference, 'Lab Preference created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LabPreference  $labPreference
     * @return \Illuminate\Http\Response
     */
    public function show(LabPreference $labPreference)
    {
        return $this->outputJSON($labPreference, 'Lab Preference retrieved');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LabPreference  $labPreference
     * @return \Illuminate\Http\Response
     */
    public function edit(LabPreference $labPreference)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LabPreference  $labPreference
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LabPreference $labPreference)
    {
        $input = $request->all();
        $input = array_filter($input);
        $labPreference->update($input);
        $labPreference->save();
        return $this->outputJSON($labPreference, 'Lab Preference updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LabPreference  $labPreference
     * @return \Illuminate\Http\Response
     */
    public function destroy(LabPreference $labPreference)
    {
        $labPreference->delete();
        return $this->outputJSON(null, 'Lab Preference deleted');

    }
}
