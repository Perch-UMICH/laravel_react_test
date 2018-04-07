<?php

namespace App\Http\Controllers;

use App\Application;;
use App\AppQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $applications = Application::all();
        return $this->outputJSON($applications, 'Applications retrieved');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Application $application)
    {
        $questions = $application->questions;
        $app = ['base' => $application, 'questions' => $questions];
        return $this->outputJSON($app, 'Application retrieved');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Application $application, Request $request)
    {
        $input = $request->all();

        // Remove old questions
        foreach($application->questions as $q) {
            $q->delete();
        }

        // Add new
        $questions = $input['questions'];
        $count = 1;
        foreach ($questions as $q) {
            $question = new AppQuestion();
            $question->question = $q;
            $question->number = $count;
            $count++;
            $question->save();
            $application->questions()->save($question);
        }

        $questions = $application->questions;
        $app = ['base' => $application, 'questions' => $questions];

        return $this->outputJSON($app,"Updated application");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Application $application)
    {
        $application->delete();

        return $this->outputJSON(null, 'Application deleted');
    }


    public function get_public_questions()
    {
        $questions = AppQuestion::where('lab_id', null)->all();

        return $this->outputJSON($questions, 'Retrieved public questions');
    }
}
