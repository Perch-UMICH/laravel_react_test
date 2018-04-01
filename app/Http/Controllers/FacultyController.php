<?php

namespace App\Http\Controllers;

use App\Faculty;
use Illuminate\Http\Request;
use App\User;

class FacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faculty = Faculty::all();
        return $this->outputJSON($faculty, 'Faculty retrieved');
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

        $faculty = Faculty::where('user_id', $request['user_id']);
        if ($faculty != null) {
            return $this->outputJSON($faculty->get(), 'Error: this user already has a faculty profile');
        }
        $user = User::find($input['user_id']);
        if ($user == null) {
            return $this->outputJSON(null, 'Error: user_id is invalid');
        }

        $faculty = new Faculty($input);
        $user->faculty()->save($faculty);
        $user->is_faculty = true;
        $user->save();

        return $this->outputJSON($faculty, 'Faculty profile created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Faculty  $faculty
     * @return \Illuminate\Http\Response
     */
    public function show(Faculty $faculty)
    {
        return $this->outputJSON($faculty,"Faculty retrieved");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Faculty  $faculty
     * @return \Illuminate\Http\Response
     */
    public function edit(Faculty $faculty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Faculty  $faculty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Faculty $faculty)
    {
        $input = $request->all();
        $input = array_filter($input);
        $faculty->update($input);
        $faculty->save();

        return $this->outputJSON($faculty, 'Faculty profile updates');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Faculty  $faculty
     * @return \Illuminate\Http\Response
     */
    public function destroy(Faculty $faculty)
    {
        $faculty->delete();

        return $this->outputJSON(null, 'Faculty profile deleted');
    }

    public function labs(Faculty $faculty) {
        $labs = $faculty->labs()->wherePivot('faculty_id', $faculty->id)->get();
        return $this->outputJSON($labs,"Labs retrieved");
    }

    public function add_lab(Request $request, Faculty $faculty) {
        $input = $request->all();
        $faculty->labs()->syncWithoutDetaching([$input['lab_id']]);
    }

    public function remove_lab(Request $request, Faculty $faculty) {
        $input = $request->all();
        $faculty->labs()->detach([$input['lab_id']]);
    }
}
