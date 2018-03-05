<?php

use App\Tag;
use App\Lab;
use App\Skill;
use App\Student;
use Illuminate\Database\Seeder;

class LabConnectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->labFacultiesSeeder();
        $this->labStudentSeeder();
        $this->labTagsTableSeeder();
        $this->labSkillsTableSeeder();
    }

    /**
     * Seeds lab_faculties table
     */
    public function labFacultiesSeeder() {
        //DB::table('faculty_lab')->delete();

        $lab = Lab::find(1);
        $lab->faculties()->sync([1]);

        $lab = Lab::find(2);
        $lab->faculties()->sync([2]);
    }

    /**
     * Seeds lab_students table
     */
    public function labStudentSeeder() {
        //DB::table('lab_students')->delete();

        $lab = Lab::find(1);
        $lab->students()->sync([1]);

        $lab = Lab::find(2);
        $lab->students()->sync([2]);
    }

    /**
     * Seeds the lab_tags table
     *
     * @return void
     */
    public function labTagsTableSeeder() {
        //DB::table('lab_tags')->delete();

        $lab = Lab::find(1);
        $lab->tags()->sync([1,2,3]);

        $lab = Lab::find(2);
        $lab->tags()->sync([4,5]);
    }

    /**
     * Seeds lab_skills table
     */
    public function labSkillsTableSeeder() {
        //DB::table('lab_skills')->delete();

        $lab = Lab::find(1);
        $lab->skills()->sync([4,5]);

        $lab = Lab::find(2);
        $lab->skills()->sync([1,2,3]);
    }

}
