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

    // Applications
    public function application(Position $position) {
        $application = $position->application;
        $questions = $application->questions;

        $app = ['base' => $application, 'questions' => $questions];
        return $this->outputJSON($app, 'Application retrieved');
    }

    public function create_application(Position $position, Request $request)
    {
        $input = $request->all();

        $application = new Application();
        $application->save();

        $position->application->delete();
        $position->application()->save($application);

        $questions = $input['questions'];
        $count = 0;
        foreach ($questions as $q) {
            $question = new AppQuestion();
            $question->question = $q;
            $question->number = $count;
            $application->questions()->save($question);
            $count++;
        }

        return $this->outputJSON($application,"Created application and added to position " . $position->title);
    }

    public function update_application(Position $position, Request $request) {
        $input = $request->all();

        $application = $position->application;
        // Remove old questions
        foreach($application->questions as $q) {
            $q->delete();
        }

        // Add new
        $questions = $input['questions'];
        $count = 0;
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

    public function app_responses(Position $position)
    {
        $responses = $position->application->responses;

        $response_data = [];
        $count = 0;
        foreach ($responses as $response) {
            if ($response->sent) {
                $response_data[$count] = Collection::make();
                $response_data[$count]->put('base', $response);
                $response_data[$count]->put('responses', $response->responses);
                $count++;
            }
        }
        return $this->outputJSON($response_data, 'Retrieved responses to this application');
    }
}
