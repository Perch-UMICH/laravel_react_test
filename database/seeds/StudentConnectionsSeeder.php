<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Skill;
use App\Student;
use App\Tag;
use App\SchoolCourse;
use App\Experience;
use App\Position;
use App\Application;
use App\AppQuestion;
use App\ApplicationResponse;
use App\AppQuestionResponse;

class StudentConnectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->studentSkillsSeeder();
        $this->studentTagsSeeder();
        $this->studentClassesSeeder();
        $this->studentExperiencesSeeder();
        $this->studentAppResponsesSeeder();
    }

    /**
     * Seeds student_skills table
     */
    public function studentSkillsSeeder() {

        $student = Student::find(1);
        $student->skills()->sync([1,2]);

        $student = Student::find(2);
        $student->skills()->sync([3,4]);
    }

    /**
     * Seeds student_tags table
     */
    public function studentTagsSeeder() {

        $student = Student::find(1);
        $student->tags()->sync([1,2]);

        $student = Student::find(2);
        $student->tags()->sync([3,4]);
    }

    public function studentClassesSeeder() {
        $class = new SchoolCourse();
        $class->title = 'EECS 281';
        $class->save();

        $class = new SchoolCourse();
        $class->title = 'MCDB 423';
        $class->save();
        $class->skills()->sync([2,4]);

        $student = Student::find(1);
        $student->school_courses()->sync([1]);

        $student = Student::find(2);
        $student->school_courses()->sync([2]);
    }

    public function studentExperiencesSeeder() {
        $exp = new Experience();
        $exp->title = 'Kataoka Research Lab';
        $exp->role = 'Petri dish cleaner IV';
        $exp->description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

        $student = Student::find(1);
        $student->experiences()->save($exp);

        $exp = new Experience();
        $exp->title = 'NASA JPL';
        $exp->role = 'Test pilot';
        $exp->description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

        $student = Student::find(2);
        $student->experiences()->save($exp);
    }

    public function studentAppResponsesSeeder() {
        $app = Application::find(1);
        $student = Student::find(1);

        $resp = new ApplicationResponse();
        $student->responses()->save($resp);
        $app->responses()->save($resp);
        $resp->save();

        foreach ($app->questions as $q) {
            $qresp = new AppQuestionResponse();
            $qresp->response = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
            $qresp->save();
            $resp->responses()->save($qresp);
            $q->responses()->save($qresp);
        }
    }

}
