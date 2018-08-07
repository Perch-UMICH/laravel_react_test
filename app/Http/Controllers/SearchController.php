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

        $projects = Position::with(['urop_position','departments'])->orderBy('lab_id')->get();
        $labs = [];
        $selected = [];
        $selected_keywords = [];

        if (empty($commitments) && empty($skills) && empty($areas) && empty($departments) && empty($keyword)) {
            $labs = Lab::with(['positions.urop_position','positions.departments'])->get();
            return $this->outputJSON(['results' => $labs, 'keyword_location' => $selected_keywords], "Search performed");
        }

        // $l->positions()->whereHas('departments', function($query) {$query->where('name','Chemistry');})->get()


        // CURRENTLY DOESN'T ACCOUNT FOR PROJS WITH MULTIPLE CLASSES AND SUBCATS
        foreach ($projects as $p) {
            $urop = $p->urop_position;
            $commitment = $p->min_time_commitment;
            $desc = $p->description;

            $classes = $urop->urop_tags->where('type', 'Classification')->pluck('name')->all();
            $cats = $urop->urop_tags->where('type', 'SubCategory')->pluck('name')->all();

            $dept = $p->departments;
            if ($dept != null) $dept = $dept->pluck('name')->toArray();
            if (!empty($dept)) $dept = $dept[0];
            else $dept = null;

            $has_commitment = (empty($commitments)
                || in_array(strtolower($commitment), array_map('strtolower', $commitments)));
            $has_skill = (empty($skills)
                || (!empty($cats) && (array_intersect(array_map('strtolower', $cats), array_map('strtolower', $skills)) == count($cats))));
            $has_area = (empty($areas)
                || (!empty($classes) && (array_intersect(array_map('strtolower', $classes), array_map('strtolower', $areas)) == count($classes))));
            $has_department = (empty($departments)
                || in_array(strtolower($dept), array_map('strtolower', $departments)));

            $has_keyword = true;
            $loc = null;
            if ($keyword) {
                $loc = stripos($desc, $keyword);
                if ($loc !== false) {
                    $has_keyword = true;
                } else {
                    $has_keyword = false;
                }
            }

            if (($has_area && $has_department) && ($has_commitment && $has_skill && $has_keyword)) {
                $selected[] = $p;
                $selected_keywords[] = $loc;
            }
        }

        // Pull labs
        $labs = [];
        $i = 0;
        foreach ($selected as $s) {
            if (empty($labs)) {
                $labs[] = $s->lab->toArray();
                $labs[$i]['positions'][] = $s->toArray();
            }
            else if ($labs[$i]['id'] == $s->lab->id) {
                $labs[$i]['positions'][] = $s->toArray();
            }
            else {
                $labs[] = $s->lab->toArray();
                $i++;
                $labs[$i]['positions'][] = $s->toArray();
            }
        }

        return $this->outputJSON(['results' => $labs, 'keyword_location' => $selected_keywords],"Search performed");
    }
}
