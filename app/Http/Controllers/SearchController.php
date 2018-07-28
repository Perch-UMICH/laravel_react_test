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
        // areas
        // department
        $input = $request->all();
        $commitments = $input['commitments']; // 6 8 10 12
        $skills = $input['skills']; // sub_cats
        $areas = $input['areas']; // classes
        $departments = $input['departments']; // depts
        $keyword = $input['keyword'];

        $projects = Position::with(['urop_position','departments'])->get();
        $selected = [];
        $selected_keywords = [];

        if (empty($commitments) && empty($skills) && empty($areas) && empty($departments) && empty($keyword))
            return $this->outputJSON(['results' => $projects, 'keyword_location' => $selected_keywords], "Search performed");


        // CURRENTLY DOESN'T ACCOUNT FOR PROJS WITH MULTIPLE CLASSES AND SUBCATS
        foreach ($projects as $p) {
            $urop = $p->urop_position;
            $commitment = $p->min_time_commitment;
            $desc = $p->description;

            $class = $urop->urop_tags->where('type', 'Classification')->first();
            if ($class) $class = $class->name;
            $cat = $urop->urop_tags->where('type', 'SubCategory')->first();
            if ($cat) $cat = $cat->name;

            $dept = $p->departments;
            if ($dept != null) $dept = $dept->pluck('name')[0];

            $has_commitment = (!empty($commitments) && in_array(strtolower($commitment), array_map('strtolower', $commitments)));
            $has_skill = ($cat && !empty($skills) && in_array(strtolower($cat), array_map('strtolower', $skills)));
            $has_area = ($class && !empty($areas) && in_array(strtolower($class), array_map('strtolower', $areas)));
            $has_department = (!empty($departments) && in_array(strtolower($dept), array_map('strtolower', $departments)));

            $has_keyword = false;
            $loc = null;

            $loc = stripos($desc, $keyword);
            if ($loc !== false) {
                $has_keyword = true;
            } else {
                $has_keyword = false;
            }

            if ($has_commitment || $has_skill || $has_area || $has_department || $has_keyword) {
                $selected[] = $p;
                $selected_keywords[] = $loc;
            }
        }

        return $this->outputJSON(['results' => $selected, 'keyword_location' => $selected_keywords],"Search performed");
    }
}
