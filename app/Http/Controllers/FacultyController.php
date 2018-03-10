<?php

namespace App\Http\Controllers;

use App\Faculty;
use Illuminate\Http\Request;

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
        $faculty = Faculty::where('user_id', $request['user_id']);
        if ($faculty) {
            return $this->outputJSON($faculty, 'Error: this user already has a faculty profile');
        }
        $faculty = new Faculty([
            'user_id' => $request->get('user_id'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'title' => $request->get('title'),
            'email' => $request->get('email'),
        ]);
        $faculty->save();
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
        $faculty->first_name = $request->get('first_name');
        $faculty->last_name = $request->get('last_name');
        $faculty->title = $request->get('title');
        $faculty->email = $request->get('email');
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
        $faculty->labs()->attach([$input['lab_id']]);
    }

    public function remove_lab(Request $request, Faculty $faculty) {
        $input = $request->all();
        $faculty->labs()->detach([$input['lab_id']]);
    }
}
