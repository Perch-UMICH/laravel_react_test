<?php

namespace App\Http\Controllers;

use App\Application;;
use App\AppQuestion;
use Illuminate\Http\Request;

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
        $input = $request->all();
        $input = array_filter($input);
        $application = new Application($input);

        return $this->outputJSON($application, 'Application created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Application $application)
    {
        return $this->outputJSON($application, 'Application retrieved');
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
        $input = array_filter($input);
        $application->update($input);
        $application->save();

        return $this->outputJSON($application, 'Application updated');
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

    // Creates a new custom question, associated with lab that created it
    public function create_question(Application $application, Request $request) {
        $input = $request->all();
        $input = array_filter($input);
        // lab_id, question

        $question = new AppQuestion($input);
        $question->save();
        $application->questions()->syncWithoutDetaching([$question->id]);

        return $this->outputJSON($question, 'Created and added question to application');
    }

    // Add existing (public or private) questions to application
    public function add_existing_questions(Application $application, Request $request) {
        $input = $request->all();
        $input = array_filter($input);
        // app_question_ids

        $ids = $input['app_question_ids'];
        $application->questions()->syncWithoutDetaching($ids);

        return $this->outputJSON(null, 'Added questions to application');
    }

    // Dissociate questions from application, but don't delete
    public function remove_questions(Application $application, Request $request) {
        $input = $request->all();
        $input = array_filter($input);

        $ids = $input['app_question_ids'];
        $application->questions()->detach($ids);

        return $this->outputJSON(null, 'Removed questions from application');
    }

    // Delete a question if it's not public
    public function delete_questions(Application $application, Request $request) {
        $input = $request->all();
        $input = array_filter($input);

        $ids = $input['app_question_ids'];
        $count = 0;
        foreach ($ids as $id) {
            $count = AppQuestion::where('id', $id)->count();
            if ($count != 0) {
                $question = AppQuestion::where('id', $id)->get();
//                if ($question->public) {
//                    return $this->outputJSON(null, 'Error: question of id ' . $id . ' is public and cannot be deleted');
//                }
                if ($question->lab_id != null) {
                    // Detach from application and delete
                    $application->questions()->detach($id);
                    $question->delete();
                    $count++;
                }
            }
        }

        return $this->outputJSON(null, 'Deleted ' . $count . ' questions');
    }

    // Update a question if it's not public
    public function update_question(AppQuestion $appQuestion, Request $request) {
        $input = $request->all();
        $input = array_filter($input);

        if (!$appQuestion->public) {
            return $this->outputJSON($appQuestion, 'Error: question of id ' . $appQuestion->id . ' is public and cannot be updated');
        }
        $appQuestion->update($input);

        return $this->outputJSON($appQuestion, 'Updated question');
    }

    public function get_public_questions() {
        $questions = AppQuestion::where('lab_id', null)->all();

        return $this->outputJSON($questions, 'Retrieved public questions');
    }
}
