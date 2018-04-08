<?php

use App\Skill;
use App\Student;
use App\Tag;
use App\SchoolCourse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

}
