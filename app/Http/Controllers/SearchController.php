<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lab;
use App\Position;
use App\Skill;
use App\Tag;
use App\Student;

use App\Department;
use App\UropTag;

class SearchController extends Controller
{
    // OLD
    public function get_search_data(Request $request) {
        $input = $request->all();

        // Student data
        $student = Student::find($input['student_id']);
        $student_interests = $student->tags()->wherePivot('student_id', $student->id)->get();
        $student_skills = $student->skills()->wherePivot('student_id', $student->id)->get();
        $student_data = [
            'data' => $student,
            'skills' => $student_skills,
            'tags' => $student_interests
        ];

        // Lab data
        $labs = Lab::all();
        $lab_data = [];
        foreach( $labs as $lab ) {
            $skills = $lab->skills()->wherePivot('lab_id', $lab->id)->get();
            $tags = $lab->tags()->wherePivot('lab_id', $lab->id)->get();
            $positions = $lab->positions()->get();
            $lab_data[$lab->id] = ['data' => $lab, 'skills' => $skills, 'tags' => $tags, 'positions' => $positions];
        }

        // Skills/Interests data
        $skills = Skill::all();
        $interests = Tag::all();

        $search_data = [
            'student_data' => $student_data,
            'lab_data' => $lab_data,
            'all_skills' => $skills,
            'all_tags' => $interests
        ];

        return $this->outputJSON($search_data,"Search data retrieved");
    }

    public function get_search_data_urop(Request $request)
    {
        $commitments = [6, 8, 10, 12];
        $departments = Department::all()->pluck('name')->toArray();
        $classifications = UropTag::where('type','Classification')->pluck('name')->toArray();
        $sub_categories = UropTag::where('type','SubCategory')->pluck('name')->toArray();

        $search_data = [
            'available_skills' => array_unique($sub_categories),
            'available_areas' => array_unique($classifications),
            'available_departments' => array_unique($departments),
            'all_commitments' => $commitments
        ];

        return $this->outputJSON($search_data,"Search data retrieved");

    }

    public function search_urop(Request $request)
    {
        // commitment
        // skills
        // tags
        // department
        $input = $request->all();
        $commitments = $input['commitments']; // 6 8 10 12
        $skills = $input['skills']; // sub_cats
        $tags = $input['tags']; // classes
        $departments = $input['departments']; // depts
        $keywords = $input['keywords'];

        $projects = Position::all();
        $selected = [];
        $selected_keywords = [];

        // CURRENTLY DOESN'T ACCOUNT FOR PROJS WITH MULTIPLE CLASSES AND SUBCATS
        foreach ($projects as $p) {
            $urop = $p->urop_position;
            $commitment = $p->min_time_commitment;
            $desc = $p->description;

            $class = $urop->urop_tags->where('type','Classification')[0]->name;
            $cat = $urop->urop_tags->where('type','SubCategory')[0]->name;
            $dept = $p->departments->pluck('name')[0];

            $has_commitment = (empty($commitments) || in_array($commitment, $commitments));
            $has_skill = (empty($skills) || in_array($cat, $skills));
            $has_tag = (empty($tags) || in_array($class, $tags));
            $has_department = (empty($departments) || in_array($dept, $departments));

            $has_keyword = false;
            $loc = null;

            if (empty($keywords)) {
                $has_keyword = true;
            }
            else {
                foreach ($keywords as $k) {
                    $loc = strpos($desc, $k);
                    if ($loc !== false) {
                        $has_keyword = true;
                    } else {
                        $has_keyword = false;
                    }
                }
            }

            if ($has_commitment && $has_skill && $has_tag && $has_department && $has_keyword) {
                $selected[] = $p;
                $selected_keywords[] = $loc;
            }
        }

        return $this->outputJSON(['results' => $selected, 'keyword_location' => $selected_keywords],"Search performed");
    }
}
