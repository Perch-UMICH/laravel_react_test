<?php

use App\Tag;
use App\Lab;
use App\Skill;
use App\Student;
use App\LabPreference;
use App\Position;
use App\Application;
use App\AppQuestion;
use App\UropPosition;
use Illuminate\Database\Seeder;

class UROPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->pb_db_seeder();
    }

    public function urop_test_seeder()
    {
        //        // UROP SKILLS ("SubCategory" in urop db)
//        $skills = ['Lab - Animal', 'Lab Research', 'Computer Programming', 'Data Collection and Analysis', 'Clinical Research', 'Community Research', 'Library/archival/internet Research', 'Experimental Research', 'Field Work'];
//        foreach ($skills as $s) {
//            $skill = new Skill();
//            $skill->name = $s;
//            $skill->save();
//        }
//
//        // UROP SUBJECTS ("Classification" in urop db)
//        $subjects = ['Social Sciences', 'Health Sciences', 'Engineering', 'Arts & Humanities', 'Life Sciences', 'Natural Sciences', 'Environmental Sciences', 'Public Health'];
//        foreach ($subjects as $s) {
//            $subject = new Tag();
//            $subject->name = $s;
//            $subject->save();
//        }

        // PROJECTS

        // Project 1

        $pos = new Position();
        $pos->title = 'Predictors of Suvival in Head and Neck Cancer';
        $pos->description = 'Longitudinal Observational Survival Study';
        $pos->min_time_commitment = 10;
        $pos->duties = 'Patient recruitment depending on their schedules 
Attend meetings
Survey mailings
Data entry
';
        $pos->min_qual = 'Good communication skills, good organizational skills, detail oriented, computer skills';

        $pos->contact_phone = '734-395-0613';
        $pos->contact_email = 'bump@umich.edu';
        $pos->location = '400 North Ingalls Building, Room 3178';

        $pos->filled = false;

        $pos->save();

        $urop = new UropPosition();
        $urop->proj_id = 10019;
        $urop->term = 1710;
        $urop->classification = 'Health Sciences';
        $urop->sub_category = 'Data Collection and Analysis';
        $urop->dept = 'School of Nursing';

        $pos->urop_position()->save($urop);

        // Group 1

        $lab = new Lab();
        $lab->name = 'Sonia Duffy\'s Group';
        $lab->location = '400 North Ingalls Building, Room 3178';
        $lab->save();

        $lab->positions()->save($pos);

        // Project 2

        $pos = new Position();
        $pos->title = 'Neurodevelopmental mechanisms of fragile X syndrome and autism spectrum disorder';
        $pos->description = 'Evolution of the cerebral cortex is thought to underlie our species’ most remarkable cognitive, perceptive, and motor capabilities, the execution of which depends on the precise establishment of axonal connectivity during development. Miswiring of cortical circuitry can lead to disorders, including autism and schizophrenia, that affect the most distinctly human cognitive functions. Research in the Kwan laboratory is aimed at understanding the developmental processes that underlie the assembly of cortical neural circuits, their evolution during the emergence of human cognition, and their dysregulation in neurodevelopmental disorders. 

Fragile X syndrome is the leading inherited form of intellectual disability and autism. The cerebral cortical mechanisms underlying this neurodevelopmental disorder, however, remain largely unexplored. This project aims to identify and characterize the genes that are translationally dyregulated in the fragile X cortex to reveal new insights into the neurobiology underpinning the disorder, which may lead to novel targets for therapeutic intervention.

Further information can be found at kwanlab.org
';
        $pos->min_time_commitment = 10;
        $pos->duties = 'The goals of this project are to identify and characterize novel molecular mechanisms of fragile X syndrome. This project will combine molecular techniques with neurobiological analyses using the mouse as an animal model. The student will be responsible for:
1) selection of candidate genes and literature review
2) characterization of candidate gene expression in the developing mouse cortex
3) molecular cloning of DNA constructs for gain- and loss-of-function experiments
4) preparation of DNA for in utero transfection surgeries
5) analysis of structural and neural circuit defects in the mouse brain
6) transfection and analysis of cultured cortical neurons 

Specific tasks and responsibilities include:
• brain dissection and fixation
• brain sectioning and immunostaining
• imaging and analysis of brain anatomy
• molecular cloning of overexpression and RNAi constructs
• western blotting
• DNA preparation for animal surgery
• primary neuronal cultures 
• DNA transfection
• animal handling and tail clipping
• purification of tail DNA and genotyping by PCR';
        $pos->min_qual = 'The student is expected to
• be willing to work with mice from young to adult ages
• learn techniques in molecular and neural biology
• be meticulous in carrying out the research
• be willing to repeat experiments as needed
• maintain excellent lab records
• become more independent as the year progresses
• be a good citizen in the lab
• work well with others in the lab
• spend a minimum of 10 hours per week in the lab in 3+ hour blocks of time ';

        $pos->contact_phone = '111-111-1111';
        $pos->contact_email = 'kkwan@umich.edu';
        $pos->location = '5041 BSRB';

        $pos->filled = false;

        $pos->save();

        $urop = new UropPosition();
        $urop->proj_id = 19228;
        $urop->term = 1710;
        $urop->classification = 'Health Sciences';
        $urop->sub_category = 'Lab - Animal';
        $urop->dept = 'School of Medicine';

        $pos->urop_position()->save($urop);

        // Group 2

        $lab = new Lab();
        $lab->name = 'Kenneth Kwan\'s Group';
        $lab->location = '5041 BSRB';
        $lab->save();

        $lab->positions()->save($pos);

        // MEMBERS

        $lab = Lab::find(1);
        $lab->members()->sync([3 => ['role' => 1], 2 => ['role' => 3]]);

        $lab = Lab::find(2);
        $lab->members()->sync([4 => ['role' => 1], 1 => ['role' => 3]]);
    }
    // Seeds db with excel file containing projects
    public function pb_db_seeder()
    {
        $file = '/storage/app/public/pb_data_test.xlsx';
        $reader = Excel::load($file);
        $data = $reader->get();

        foreach ($data as $d) {
            // Check for project duplicate
            $projs = Position::all();
            $titles = [];
            foreach ($projs as $p) {
                $titles[] = $p->title;
            }
            if (!in_array($d['projtitle'], $titles)) {
                $pos = new Position();
                $pos->title = $d['projtitle'];
                $pos->description = $d['projdescr'];
                $pos->min_time_commitment = intval($d['hrsperweek'][0]);
                $pos->duties = $d['duties'];
                $pos->min_qual = $d['minqual'];

                $pos->contact_phone = '111-111-1111';
                $pos->contact_email = 'email@email.com';
                $pos->location = $d['location'];

                $pos->filled = false;

                $pos->save();

                $urop = new UropPosition();
                $urop->proj_id = $d['projid'];
                $urop->term = $d['term'];
                $urop->classification = $d['classifications'];
                $urop->sub_category = $d['subcategory'];
                $urop->dept = $d['dept'];
                $urop->learning_comp = $d['learningcomp'];
                $urop->training = $d['training'];

                $pos->urop_position()->save($urop);

                // Group 1

                $lab = new Lab();
                $lab->name = $d['name'] . '\'s Group';
                $lab->location = $d['location'];
                $lab->save();

                $lab->positions()->save($pos);
            }
        }
    }

}
