<?php

use App\Tag;
use App\Lab;
use App\Skill;
use App\Student;
use App\LabPreference;
use App\Position;
use App\Application;
use App\AppQuestion;
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
        //$this->labFacultiesSeeder();
        $this->labRolesSeeder();
        $this->labMembersSeeder();
        //$this->labStudentSeeder();
        $this->labTagsTableSeeder();
        $this->labSkillsTableSeeder();
        //$this->labPreferencesTableSeeder();
        $this->labPositionsTableSeeder();
    }

    /**
     * Seeds labroles
     */
    public function labRolesSeeder() {
        DB::table('labroles')->delete();
        DB::table('labroles')->insert([
            ['role' => 'PI'],
            ['role' => 'Graduate'],
            ['role' => 'Undergraduate'],
            ['role' => 'Alumni']
        ]);
    }

    /**
     * Seeds lab_user table
     */
    public function labMembersSeeder() {
        $lab = Lab::find(1);
        $lab->members()->sync([3 => ['role' => 1], 2 => ['role' => 3]]);

        $lab = Lab::find(2);
        $lab->members()->sync([4 => ['role' => 1], 2 => ['role' => 3]]);

    }

    /**
     * Seeds lab_students table
     */
    public function labStudentSeeder() {
        //DB::table('lab_students')->delete();

//        $lab = Lab::find(1);
//        $lab->students()->sync([1]);
//
//        $lab = Lab::find(2);
//        $lab->students()->sync([2]);
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

        // Lab 1

        $pos = new Position();
        $pos->title = 'Wireless Snow Sensor';
        $pos->proj_id = 10010;
        $pos->description = 'Snowpack properties such as density and content of liquid water are very difficult quantities to measure.  Microwave resonant cavities can be used to measure these properties, but so far they have all been bulky and expensive.  Being wired devices, they cannot be installed in the snow for long periods, as heat moves thru the wires and partly melts the snow near the sensor, ruining the measurement.  I would like to develop a wireless sensor to measure these snow properties.  One application could be an avalanche warning system, as snowpack with liquid water at its base is more likely to avalanche than snowpack that is dry and frozen.';
        $pos->min_time_commitment = 10;
        $pos->duties = 'The path to a completed system will likely take several years, but each year (06-07 being the first) affords some UROP opportunities.  Microcontroller programming, verification of vendor specifications, circuit board design and fabrication, laboratory and field testing are some activities that will involve students.';
        $pos->min_qual = 'Sophomore standing preferred; completion of Engr 100 preferred.
Students:  when applying, send me by email 1. your resume, so I understand your interests, 2. an unofficial transcript, so I can understand your educational background, and 3. your schedule, to help me schedule an appointment';
        $pos->benefits = '-product design, test and evaluation
-literature search (material properties)
-lab skills (soldering, drill press, etc)
-engineering documentation techniques
-possibly some programming';
        $pos->filled = false;

        $lab = Lab::find(2);
        $lab->positions()->save($pos);

        // Lab 2

        $pos = new Position();
        $pos->title = 'Nanosatellite modeling and design';
        $pos->description = 'Develop novel small satellite missions built on fundamental advancements in spacecraft technology.';
        $pos->time_commitment = '8-10 hours/week';
        $pos->open_slots = 3;

        $lab->positions()->save($pos);

        // App with generic and lab owned questions
        $app = new Application();
        $pos->application()->save($app);
        $app->save();

        $q = new AppQuestion();
        $q->question = 'Lab question 1';
        $q->number = 1;
        $q->save();
        $app->questions()->save($q);

        $q = new AppQuestion();
        $q->question = 'Lab question 2';
        $q->number = 2;
        $q->save();

        $app->questions()->save($q);

        $q = new AppQuestion();
        $q->question = 'Lab question 3';
        $q->number = 3;
        $q->save();

        $app->questions()->save($q);
    }

}
