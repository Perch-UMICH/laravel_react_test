<?php

namespace App\Http\Controllers;

use App\AppQuestion;
use App\Lab;
use App\Position;
use App\Application;
use App\Skill;
use Doctrine\DBAL\Tools\Console\Command\ReservedWordsCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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
            $skills = $this->skills($lab)->original;
            $tags = $this->tags($lab)->original;
            if (array_key_exists('result',$skills)) $skills = $skills['result'];
            else $skills = null;
            if (array_key_exists('result',$tags)) $tags = $tags['result'];
            else $tags = null;

            $positions = $lab->positions()->get();
            $lab_data[$lab->id] = ['data' => $lab, 'skills' => $skills, 'tags' => $tags];
        }
        return $this->outputJSON($lab_data,"Labs retrieved");
    }

    public function get_all_labs(Request $request) {
        $input = $request->all();
        //$input = array_filter($input);
        // skilltag_data, preferences_data, position_data, application_data, student_data, faculty_data

        $labs = Lab::all();

        $lab_data = [];

        foreach( $labs as $lab ) {
            $lab_data[$lab->id] = Collection::make();
            $lab_data[$lab->id]->put('data', $lab);

//            if ($input['skilltag_data']) {
//                $skills = $lab->skills()->wherePivot('lab_id', $lab->id)->get();
//                $tags = $lab->tags()->wherePivot('lab_id', $lab->id)->get();
//                $lab_data[$lab->id]->put('skills', $skills);
//                $lab_data[$lab->id]->put('tags', $tags);
//
//            }
            if ($input['position_data']) {
                $positions = $lab->positions()->get();
                $lab_data[$lab->id]->put('positions', $positions);
            }
            if ($input['application_data']) {
                $applications = $lab->applications()->get();
                $application_data = [];
                foreach ($applications as $app) {
                    $qs = $app->questions()->get();
                    $application_data[$app->id] = ['data' => $app, 'questions' => $qs];
                }
                $lab_data[$lab->id]->put('applications', $application_data);

            }
            if ($input['student_data']) {
                $members = $lab->members()->wherePivot('lab_id', $lab->id)->get();
                $students = [];
                foreach ($members as $member) {
                    if ($member->is_student) $students[] = $member->student;
                }
                $lab_data[$lab->id]->put('students', $students);

            }
            if ($input['faculty_data']) {
                $members = $lab->members()->wherePivot('lab_id', $lab->id)->get();
                $faculties = [];
                foreach ($members as $member) {
                    if ($member->is_faculty) $faculties[] = $member->faculty;
                }
                $lab_data[$lab->id]->put('faculties', $faculties);
            }
            if ($input['preferences_data']) {
                $preferences = $lab->preferences()->wherePivot('lab_id', $lab->id)->get();
                $lab_data[$lab->id]->put('preferences', $preferences);
            }
        }

        return $this->outputJSON($lab_data, 'All lab data retrieved');
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

        $lab = new Lab($input);
        $lab->save();

        // Make user owner of this group
        $user = $request->user();
        $lab->members()->syncWithoutDetaching([$user->id => ['role' => 1]]);

        $lab->members;

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
        $skills = $this->skills($lab)->original;
        $tags = $this->tags($lab)->original;

        if (array_key_exists('result',$skills)) $skills = $skills['result'];
        else $skills = null;
        if (array_key_exists('result',$tags)) $tags = $tags['result'];
        else $tags = null;

        $lab_data = ['data' => $lab, 'skills' => $skills, 'tags' => $tags];
        return $this->outputJSON($lab_data,"Lab retrieved");
    }

    public function get_lab(Lab $lab, Request $request) {
        $input = $request->all();
        //$input = array_filter($input);
        // skilltag_data, preferences_data, position_data, application_data, student_data, faculty_data

        $lab_data = Collection::make();
        $lab_data->put('data', $lab);

        if ($input['position_data']) {
            $positions = $lab->positions()->get();
            $lab_data->put('positions', $positions);
        }
        if ($input['application_data']) {
            $applications = $lab->applications()->get();
            $application_data = [];
            foreach ($applications as $app) {
                $qs = $app->questions()->get();
                $application_data[$app->id] = ['data' => $app, 'questions' => $qs];
            }
            $lab_data->put('applications', $application_data);

        }
        if ($input['student_data']) {
            $members = $lab->members()->wherePivot('lab_id', $lab->id)->get();
            $students = [];
            foreach ($members as $member) {
                if ($member->is_student) $students[] = $member->student;
            }
            $lab_data->put('students', $students);

        }
        if ($input['faculty_data']) {
            $members = $lab->members()->wherePivot('lab_id', $lab->id)->get();
            $faculties = [];
            foreach ($members as $member) {
                if ($member->is_faculty) $faculties[] = $member->faculty;
            }
            $lab_data->put('faculties', $faculties);
        }
        if ($input['preferences_data']) {
            $preferences = $lab->preferences()->wherePivot('lab_id', $lab->id)->get();
            $lab_data->put('preferences', $preferences);
        }

        return $this->outputJSON($lab_data, 'Lab data retrieved');
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
        //$input = array_filter($input);
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

    // LAB ASSOCIATIONS
    // NOTE: skill/tag additions are made to positions, not labs directly

    // Skills (derived from positions):

    public function skills(Lab $lab) {
        $skills = [];
        $skill_ids = [];
        $positions = $lab->positions;
        foreach ($positions as $p) {
            $p_skills = $p->skills;
            foreach ($p_skills as $s) {
                $skill_ids[] = $s->id;
            }
        }
        array_unique($skill_ids);
        foreach ($skill_ids as $id) {
            $skills[] = Skill::find($id);
        }
        return $this->outputJSON($skills,"Skills from lab retrieved");
    }

    public function add_skill(Request $request) {
        $input = $request->all();
        $ids = $input['skill_ids'];
        $position_id = $input['position_id'];
        $position = Position::find($position_id);

        if ($position === null) return $this->outputJSON(null,"Error: invalid position_id",404);

        $position->skills()->syncWithoutDetaching($ids);
        return $this->outputJSON(null,"Added skills");
    }

    public function sync_skills(Request $request) {
        $input = $request->all();
        $ids = $input['skill_ids'];
        $position_id = $input['position_id'];
        $position = Position::find($position_id);

        if ($position === null) return $this->outputJSON(null,"Error: invalid position_id",404);

        $position->skills()->sync($ids);
        return $this->outputJSON(null,"Synced skills");
    }

    public function remove_skill(Request $request) {
        $input = $request->all();
        $ids = $input['skill_ids'];
        $position_id = $input['position_id'];
        $position = Position::find($position_id);

        if ($position === null) return $this->outputJSON(null,"Error: invalid position_id",404);

        $position->skills()->detach($ids);
        return $this->outputJSON(null,"Removed skills");
    }

    // Tags (derived from positions):

    public function tags(Lab $lab) {
        $tags = [];
        $tag_ids = [];
        $positions = $lab->positions;
        foreach ($positions as $p) {
            $p_tags = $p->tags;
            foreach ($p_tags as $t) {
                $tag_ids[] = $t->id;
            }
        }
        array_unique($tag_ids);
        foreach ($tag_ids as $id) {
            $tags[] = Tag::find($id);
        }
        return $this->outputJSON($tags,"Tags from lab retrieved");
    }

    public function add_tag(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['tag_ids'];
        $position_id = $input['position_id'];
        $position = Position::find($position_id);

        if ($position === null) return $this->outputJSON(null,"Error: invalid position_id",404);

        $position->tags()->syncWithoutDetaching($ids);
        return $this->outputJSON(null,"Added tags");
    }

    public function sync_tags(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['tag_ids'];
        $position_id = $input['position_id'];
        $position = Position::find($position_id);

        if ($position === null) return $this->outputJSON(null,"Error: invalid position_id",404);

        $position->tags()->sync($ids);
        return $this->outputJSON(null,"Synced tags");
    }

    public function remove_tag(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['tag_ids'];
        $position_id = $input['position_id'];
        $position = Position::find($position_id);

        if ($position ===null) return $this->outputJSON(null,"Error: invalid position_id",404);

        $position->tags()->detach($ids);
        return $this->outputJSON(null,"Removed tags");
    }

    // Members:

    // getLabMembers
    public function members(Lab $lab) {
        $members = $lab->members()->with('student', 'faculty', 'files')->get();

        $students = [];
        $faculties = [];
        foreach ($members as $member) {
            if ($member->is_faculty) $faculties[] = ['data' => $member, 'role' => $member->pivot->role];
            if ($member->is_student) $students[] = ['data' => $member, 'role' => $member->pivot->role];
        }

        return $this->outputJSON(['students' => $students, 'faculty' => $faculties],"Members from lab retrieved");
    }

    public function add_members(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['user_ids'];
        $roles = $input['role_ids'];

        if (count($ids) != count($roles)) {
            return $this->outputJSON(null,"Error: different number of user_ids and roles", 500);
        }

        $count = 0;
        foreach ($ids as $id) {
            $role = $roles[$count];
            $lab->members()->syncWithoutDetaching([$id =>  ['role' => $role]]);
            $count++;
        }
        return $this->outputJSON(null,"Added " . count($ids) . " members to lab");
    }

    public function update_member(Request $request, Lab $lab) {
        $input = $request->all();
        $id = $input['user_id'];
        $role = $input['role_id'];

//        // Check for user removing themselves
//        $user = $request->user();
//        $user_mem = $lab->members()->where('id', $user->id)->get();

        $mem = $lab->members()->where('id', $id)->get();

        if ($mem->pivot->role == 1)
        {
            return $this->outputJSON($mem,"Error: you can't remove the owner from the lab", 500);
        }

        $lab->members()->updateExistingPivot($id, ['role' => $role]);
        $member = $lab->members()->where('id', $id)->get();

        return $this->outputJSON($member,"Updated membership");
    }

    public function remove_members(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['user_ids'];

        $lab->members()->detach($ids);
        return $this->outputJSON(null,"Removed " . count($ids) . " members from lab");
    }

    // Positions:

    public function position(Request $request, Lab $lab, Position $position) {
        $position->application->questions;

        if (!$this->check_lab_position_association($lab, $position))
            return $this->outputJSON(null,"Error: position of id " . $position->id . " does not belong to a lab this user is admin of");

        return $this->outputJSON($position,"Position from lab retrieved");
    }

    public function positions(Lab $lab) {
        //$positions = $lab->positions()->with('application.questions')->get();
        $positions = $lab->positions()->get();
        return $this->outputJSON($positions,"Positions from lab retrieved");
    }

    public function create_position(Request $request, Lab $lab) {
        $input = $request->all();

        // Create Position
        $position = new Position($input);
        $lab->positions()->save($position);
        $position->save();

        // Create Application
        $app = new Application();
        $position->application()->save($app);

        // Attach questions to Application
        $questions = $input['application']['questions'];

        $count = 0;
        foreach ($questions as $q) {
            $question = new AppQuestion();
            $question->question = $q['question'];
            $question->number = $count;
            $count++;
            $app->questions()->save($question);
        }


        return $this->outputJSON($position,"Created position " . $position->title . " and added to lab " . $lab->name);
    }

    public function update_position(Request $request, Lab $lab, Position $position) {
        if (!$this->check_lab_position_association($lab, $position))
            return $this->outputJSON(null,"Error: position of id " . $position->id . " does not belong to a lab this user is admin of");

        $input = $request->all();
        //$input = array_filter($input);

        // Update Position
        $position->update($input);
        $position->save();

        // Update Application
        $app = $position->application;
        // Add updated questions (if applicable)
        if (array_key_exists ('questions', $input['application'])) {
//            // Remove old questions
//            foreach($app->questions as $q) {
//                $q->delete();
//            }

            $questions = $input['application']['questions'];
            foreach ($questions as $q) {
                $question = $app->questions()->where('number','=',$q['number'])->first();
                if ($question == null) continue;
                $question->question = $q['question'];
                $app->questions()->save($question);
            }
        }

        $p = Position::find($position->id);
        $p->application->questions;

        return $this->outputJSON($p, 'Lab Position updated');
    }

    public function delete_position(Request $request, Lab $lab, Position $position) {
        if (!$this->check_lab_position_association($lab, $position))
            return $this->outputJSON(null,"Error: position of id " . $position->id . " does not belong to a lab this user is admin of");

        $id = $position->id;
        $app = $position->application;
        foreach($app->questions as $q) {
            $q->delete();
        }
        $app->delete();

        return $this->outputJSON(null,"Deleted position of id " . $id);
    }

    public function check_lab_position_association(Lab $lab, Position $position) {
        $intended_lab = $position->lab;
        return ($lab->id == $intended_lab->id);
    }

    // Applications:
    // Takes position_id as input

//    public function application(Request $request, Lab $lab) {
//        $input = $request->all();
//        $pos = Position::find($input['position_id']);
//        $application = $pos->application;
//        if (!$application) return $this->outputJSON(null, 'Error: position has no application associated with it');
//        $application->questions;
//
//        return $this->outputJSON($application, 'Application retrieved');
//    }

//    public function create_application(Request $request, Lab $lab)
//    {
//        $input = $request->all();
//
//        $position = Position::find($input['position_id']);
//        if (!$position)
//            return $this->outputJSON(null,"Error: invalid position_id");
//
//        $intended_lab = $position->lab;
//
//        if ($lab->id != $intended_lab->id)
//            return $this->outputJSON(null,"Error: position of id " . $position->id . " does not belong to a lab this user is admin of");
//
//        $application = new Application();
//        $application->save();
//
//        $position->application()->delete();
//        $position->application()->save($application);
//
//        $questions = $input['questions'];
//        foreach ($questions as $q) {
//            $question = new AppQuestion();
//            $question->question = $q;
//            $application->questions()->save($question);
//        }
//
//        $application->questions;
//
//        return $this->outputJSON($application,"Created application and added to position " . $position->title);
//    }

//    public function update_application(Request $request, Lab $lab) {
//        $input = $request->all();
//
//        $position = Position::find($input['position_id']);
//        if ($position === null)
//            return $this->outputJSON(null,"Error: invalid position_id");
//
//        $intended_lab = $position->lab;
//
//        if ($lab->id != $intended_lab->id)
//            return $this->outputJSON(null,"Error: position of id " . $position->id . " does not belong to a lab this user is admin of");
//
//        $application = $position->application;
//        // Remove old questions
//        foreach($application->questions as $q) {
//            $q->delete();
//        }
//
//        // Add new
//        $questions = $input['questions'];
//        foreach ($questions as $q) {
//            $question = new AppQuestion();
//            $question->question = $q;
//            $question->save();
//            $application->questions()->save($question);
//        }
//
//        $application->questions;
//
//        return $this->outputJSON($application,"Updated application");
//    }

//    // getPositionApplication
//    public function position_application(Request $request, Lab $lab) {
//        $position_id = $request->route()->parameters['position'];
//        $position = $lab->positions()->where('id', $position_id)->first();
//        if (!$position) return $this->outputJSON(null, 'Error: invalid position id', 400);
//        $app = $position->application;
//        $app->questions;
//
//        return $this->outputJSON($app, 'Application retrieved');
//    }

    // getLabPositionApplicationResponses
    public function position_application_responses(Request $request, Lab $lab, Position $position) {
        if (!$this->check_lab_position_association($lab, $position))
            return $this->outputJSON(null,"Error: position of id " . $position->id . " does not belong to a lab this user is admin of");

        $resp = $position->responses()->with('answers')->get(); //TODO add where('sent','true')

        return $this->outputJSON($resp, 'Responses retrieved');
    }


//    // App Responses:
//
//    public function app_responses(Request $request, Lab $lab, Position $position) {
//        if (!$this->check_lab_position_association($lab, $position))
//            return $this->outputJSON(null,"Error: position of id " . $position->id . " does not belong to a lab this user is admin of");
//
//        $application = $position->application;
//        if (!$application) return $this->outputJSON(null, 'Error: position has no application associated with it');
//        $responses = $application->responses()->with('answers')->get();
//
//        return $this->outputJSON($responses, 'Retrieved responses to this application');
//    }


    // Challenge:

    public function challenge_project_data() {

        $positions = Position::all()->random(10);

        $poses = [];

        foreach ($positions as $p)
        {
            $pos = (object)array();
            $pos->id = $p->id;
            $pos->title = $p->title;
            $pos->description = str_replace("_x000D_","",$p->description);
            $pos->duties = str_replace("_x000D_","",$p->duties);
            $pos->time_commitment = $p->min_time_commitment;
            $pos->classification = $p->urop_position->classification;
            $poses[] = $pos;
        }

        return $this->outputJSON($poses, 'Retrieved positions');
    }
}
