<?php

namespace App\Http\Controllers;

use App\ClassExperience;
use App\EduExperience;
use App\Student;
use App\Major;

use App\University;
use Illuminate\Http\Request;

class EduExperienceController extends Controller
{
    public function store(Request $request, Student $student)
    {
        $input = $request->all();
        $input = array_filter($input);

        $university_name = $input['university_name'];
        $major_names = $input['major_names'];
        $class_experience_names = $input['class_experience_names'];

        $uni = University::where('name',$university_name)->first();
        if (!$uni) $uni = University::create(['name' => $university_name]);

        $major_ids = [];
        foreach ($major_names as $m) {
            $major = Major::where('name',$m)->first();
            if (!$major) $major = Major::create(['name' => $m]);
            $major_ids[] = $major->id;
        }

        $class_ids = [];
        foreach ($class_experience_names as $c) {
            $class = ClassExperience::where('name',$c)->first();
            if (!$class) $class = ClassExperience::create(['name' => $c]);
            $class_ids[] = $class->id;
        }

        $edu_exp = EduExperience::create($input);

        $uni->edu_experiences()->save($edu_exp);
        $student->edu_experiences()->save($edu_exp);

        $edu_exp->majors()->syncWithoutDetaching($major_ids);
        $edu_exp->classes()->syncWithoutDetaching($class_ids);

        return $this->outputJSON($edu_exp,"Created edu experience",200);
    }

    public function update(Request $request, Student $student)
    {
        $input = $request->all();
        $input = array_filter($input);

        $edu_exp_id = $input['edu_experience_id'];
        $edu_exp = EduExperience::find($edu_exp_id);
        if (!$edu_exp) return $this->outputJSON(null,"Error: invalid edu_experience id",404);

        $edu_exp->update($input);

        if (array_has($input,'university_name')) {
            $university_name = $input['university_name'];
            $uni = University::where('name',$university_name)->first();
            if (!$uni) $uni = University::create(['name' => $university_name]);
            $edu_exp->university_id = $uni->id;
        }

        if (array_has($input,'major_names')) {
            $major_names = $input['major_names'];
            $major_ids = [];
            foreach ($major_names as $m) {
                $major = Major::where('name',$m)->first();
                if (!$major) $major = Major::create(['name' => $m]);
                $major_ids[] = $major->id;
            }
            $edu_exp->majors()->sync($major_ids);
        }

        if (array_has($input,'class_experience_names')) {
            $class_experience_names = $input['class_experience_names'];
            $class_ids = [];
            foreach ($class_experience_names as $c) {
                $class = ClassExperience::where('name',$c)->first();
                if (!$class) $class = ClassExperience::create(['name' => $c]);
                $class_ids[] = $class->id;
            }
            $edu_exp->classes()->sync($class_ids);
        }

        return $this->outputJSON($edu_exp,"Updated edu experience",200);
    }

    public function delete(Request $request, Student $student)
    {
        $input = $request->all();
        $edu_exp_ids = $input['edu_experience_ids'];

        foreach ($edu_exp_ids as $id) {
            $edu_exp = EduExperience::find($id);
            if (!$edu_exp) return $this->outputJSON(null,"Error: invalid edu_experience id",404);
        }
        EduExperience::destroy($edu_exp_ids);
        return $this->outputJSON(null,"Deleted edu experience",200);
    }
}
