<?php

use App\Tag;
use App\Lab;
use App\Skill;
use App\Student;
use App\LabPreference;
use App\Position;
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
        $this->labPreferencesTableSeeder();
        $this->labPositionsTableSeeder();
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

    public function labPreferencesTableSeeder() {
        $preference = new LabPreference();
        $preference->type = 'No';
        $preference->title = 'Seniors';
        $preference->save();

        $preference = new LabPreference();
        $preference->type = 'No';
        $preference->title = 'Life';
        $preference->save();

        $preference = new LabPreference();
        $preference->type = 'Yes';
        $preference->title = 'Credit';
        $preference->save();

        $lab = Lab::find(1);
        $lab->preferences()->sync([1,2]);

        $lab = Lab::find(2);
        $lab->preferences()->sync([2,3]);
    }

    public function labPositionsTableSeeder() {
        $pos = new Position();
        $pos->title = 'Computational drug discovery';
        $pos->description = 'Design novel drug combination therapies using computational approaches.';
        $pos->time_commitment = '10-12 hours/week';
        $pos->open_slots = 2;
        $pos->filled_slots = 0;
        $pos->open = true;

        $lab = Lab::find(2);
        $lab->positions()->save($pos);

        $pos = new Position();
        $pos->title = 'Nanosatellite modeling and design';
        $pos->description = 'Develop novel small satellite missions built on fundamental advancements in spacecraft technology.';
        $pos->time_commitment = '8-10 hours/week';
        $pos->open_slots = 3;
        $pos->filled_slots = 0;
        $pos->open = true;

        $lab->positions()->save($pos);
    }

}
