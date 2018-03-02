<?php

use App\Skill;
use App\Student;
use App\Tag;
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

}
