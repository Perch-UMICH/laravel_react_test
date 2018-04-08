<?php

namespace App\Http\Controllers;

use App\AppQuestion;
use App\Faculty;
use App\Lab;
use App\Position;
use App\Application;
use App\Student;
use App\User;
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
            $skills = $lab->skills()->wherePivot('lab_id', $lab->id)->get();
            $tags = $lab->tags()->wherePivot('lab_id', $lab->id)->get();
            $positions = $lab->positions()->get();
            $lab_data[$lab->id] = ['data' => $lab, 'skills' => $skills, 'tags' => $tags, 'positions' => $positions];
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

            if ($input['skilltag_data']) {
                $skills = $lab->skills()->wherePivot('lab_id', $lab->id)->get();
                $tags = $lab->tags()->wherePivot('lab_id', $lab->id)->get();
                $lab_data[$lab->id]->put('skills', $skills);
                $lab_data[$lab->id]->put('tags', $tags);

            }
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

        $lab = Lab::where('name', $request['name']);
        if ($lab == null) {
            return $this->outputJSON($lab, 'Error: lab with this name already exists');
        }

        $lab = new Lab($input);
        $lab->save();

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
        $skills = $lab->skills()->wherePivot('lab_id', $lab->id)->get();
        $tags = $lab->tags()->wherePivot('lab_id', $lab->id)->get();
        $lab_data = ['data' => $lab, 'skills' => $skills, 'tags' => $tags];
        return $this->outputJSON($lab_data,"Lab retrieved");
    }

    public function get_lab(Lab $lab, Request $request) {
        $input = $request->all();
        //$input = array_filter($input);
        // skilltag_data, preferences_data, position_data, application_data, student_data, faculty_data

        $lab_data = Collection::make();
        $lab_data->put('data', $lab);

        if ($input['skilltag_data']) {
            $skills = $lab->skills()->wherePivot('lab_id', $lab->id)->get();
            $tags = $lab->tags()->wherePivot('lab_id', $lab->id)->get();
            $lab_data->put('skills', $skills);
            $lab_data->put('tags', $tags);

        }
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
        $input = array_filter($input);
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

    // Lab associations

    // Retrieve

    public function students(Lab $lab) {
        $members = $lab->members()->wherePivot('lab_id', $lab->id)->get();
        $students = [];
        foreach ($members as $member) {
            if ($member->is_student) $students[] = $member->student;
        }
        return $this->outputJSON($students,"Students from lab retrieved");
    }

    public function faculties(Lab $lab) {
        $members = $lab->members()->wherePivot('lab_id', $lab->id)->get();
        $faculties = [];
        foreach ($members as $member) {
            if ($member->is_faculty) $faculties[] = $member->faculty;
        }
        return $this->outputJSON($faculties,"Faculties from lab retrieved");
    }

    public function skills(Lab $lab) {
        $skills = $lab->skills()->wherePivot('lab_id', $lab->id)->get();
        return $this->outputJSON($skills,"Skills from lab retrieved");
    }

    public function tags(Lab $lab) {
        $tags = $lab->tags()->wherePivot('lab_id', $lab->id)->get();
        return $this->outputJSON($tags,"Tags from lab retrieved");
    }

    public function preferences(Lab $lab) {
        $preferences = $lab->preferences()->wherePivot('lab_id', $lab->id)->get();
        return $this->outputJSON($preferences,"Preferences from lab retrieved");
    }

    public function positions(Lab $lab) {
        $positions = $lab->positions()->get();
        return $this->outputJSON($positions,"Positions from lab retrieved");
    }

//    public function applications(Lab $lab) {
//        $applications = $lab->applications()->get();
//        $application_data = [];
//        foreach($applications as $app) {
//            $qs = $app->questions()->get();
//            $application_data[$app->id] = ['data' => $app, 'questions' => $qs];
//        }
//        return $this->outputJSON($application_data,"Applications from lab retrieved");
//    }

    // Add

    public function add_skill(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['skill_ids'];
        $lab->skills()->syncWithoutDetaching($ids);
        return $this->outputJSON(null,"Added skills");
    }

    public function sync_skills(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['skill_ids'];
        $lab->skills()->sync($ids);
        return $this->outputJSON(null,"Synced skills");
    }

    public function add_tag(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['tag_ids'];
        $lab->tags()->syncWithoutDetaching($ids);
        return $this->outputJSON(null,"Added tags");
    }

    public function sync_tags(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['tag_ids'];
        $lab->tags()->sync($ids);
        return $this->outputJSON(null,"Synced tags");
    }

    public function add_students(Request $request, Lab $lab) {
        // extract requests
        $input = $request->all();
        $student_ids = $input['student_ids'];
        $roles = $input['roles'];
        // array for sync
        $ids = [];
        // find user id from student id
        for ($i = 0; $i < count($student_ids); $i++) {
            $id = $student_ids[$i];
            $role = $roles[$i];
            $student = Student::where('id', $id)->first();
            // if exist, add to id
            if ($student) {
                $userid = $student->User->id;
                $ids[$userid] = ['role' => $role];
            }
        }
        $lab->members()->syncWithoutDetaching($ids);
        return $this->outputJSON(null,"Added students");
    }

    public function add_faculties(Request $request, Lab $lab) {
        $input = $request->all();
        $faculty_ids = $input['faculty_ids'];
        $roles = $input['roles'];
        $ids = [];
        for ($i = 0; $i < count($faculty_ids); $i++) {
            $id = $faculty_ids[$i];
            $role = $roles[$i];
            $faculty = Faculty::where('id',$id)->first();
            if ($faculty) {
                $userid = $faculty->User->id;
                $ids[$userid] = ['role' => $role];
            }
        }
        $lab->members()->syncWithoutDetaching($ids);
        return $this->outputJSON(null,"Added faculty");
    }

    public function add_preference(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['preference_ids'];
        $lab->preferences()->syncWithoutDetaching($ids);
        return $this->outputJSON(null,"Added preferences");
    }

    public function create_position(Request $request, Lab $lab) {
        $input = $request->all();
        $position = new Position($input);
        $lab->positions()->save($position);
        $position->save();

        return $this->outputJSON($position,"Created position " . $position->title . " and added to lab " . $lab->name);
    }

//    public function create_application(Request $request, Lab $lab) {
//        $input = $request->all();
//        $application = new Application($input);
//        $application->save();
//
//        $questions = $input['questions'];
//        foreach ($questions as $q) {
//            $question = new AppQuestion();
//            $question->question = $q;
//            $application->questions()->save($question);
//        }
//
//        $pos = $application->position;
//        $pos->applications()->save($application);
//        $pos->save();
//
//        return $this->outputJSON($application,"Created application and added to position " . $pos->title);
//    }


    // Remove

    public function remove_skill(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['skill_ids'];
        $lab->skills()->detach($ids);
        return $this->outputJSON(null,"Removed skills from lab " . $lab->name);
    }

    public function remove_tag(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['tag_ids'];
        $lab->tags()->detach($ids);
        return $this->outputJSON(null,"Removed tags from lab " . $lab->name);
    }

    public function remove_student(Request $request, Lab $lab) {
        $input = $request->all();
        $student_ids = $input['student_ids'];
        $ids = [];
        foreach($student_ids as $id) {
            $student = Student::find($id)->first();
            if ($student) {
                $userid = $student->User->id;
                $ids[] = $userid;
            }
        }
        $lab->members()->detach($ids);
        return $this->outputJSON(null,"Removed students from lab " . $lab->name);
    }

    public function remove_faculty(Request $request, Lab $lab) {
        // parse request
        $input = $request->all();
        $faculty_ids = $input['faculty_ids'];
        // array for detach
        $ids = [];
        foreach($faculty_ids as $id) {
            $faculty = Faculty::where('id',$id)->first();
            if ($faculty) {
                $userid = $faculty->User->id;
                $ids[] = $userid;
            }
        }
        $lab->members()->detach($ids);
        return $this->outputJSON(null,"Removed faculty from lab " . $lab->name);
    }

    public function remove_preference(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['preference_ids'];
        $lab->preferences()->detach($ids);
        return $this->outputJSON(null,"Removed preferences from lab " . $lab->name);
    }

    public function delete_positions(Request $request, Lab $lab) {
        $input = $request->all();
        $ids = $input['position_ids'];
        Position::destroy($ids);

        return $this->outputJSON(null,"Deleted positions from lab " . $lab->name);
    }

//    public function delete_applications(Request $request, Lab $lab) {
//        $input = $request->all();
//        $ids = $input['application_ids'];
//        Application::destroy($ids);
//
//        return $this->outputJSON(null,"Deleted applications from lab " . $lab->name);
//    }
}
