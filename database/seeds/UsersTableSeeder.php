<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Student;
use App\Faculty;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        DB::table('students')->delete();

        // Create student user
        $student = new User();
        $student->name = 'akshayro';
        $student->email = 'akshayro@umich.edu';
        $student->password = bcrypt('password');
//        $student->login_method_id = 1;
        $student->is_student = true;
        $student->is_faculty = false;
        $student->save();

        // Create student profile object for student
        $profile = new Student();
        $profile->user_id = $student->id;
        $profile->first_name = 'Akshay';
        $profile->last_name = 'Rao';
//        $profile->major = 'Physics';
//        $profile->year = 'Junior';
//        $profile->gpa = '4.0';
        $profile->contact_email = $student->email;
//        $profile->classes = 'MCDB 423|CHEM 215/216';
//        $profile->experiences = 'Dr. Kataoka\'s Alchemy Lab (Fall 2016 - Fall 2017)|U of M Neuroimaging Lab (Summer 2017)';
        $profile->save();

        // Create additional users
        $student = new User();
        $student->name = 'perch';
        $student->email = 'student@perch.com';
        $student->password = bcrypt('test');
//        $student->login_method_id = 1;
        $student->is_student = true;
        $student->is_faculty = false;
        $student->save();

        $profile = new Student();
        $profile->user_id = $student->id;
        $profile->first_name = 'Perch';
        $profile->last_name = 'User';
//        $profile->year = 'Freshman';
//        $profile->major = 'Biology';
        $profile->contact_email = $student->email;
//        $profile->classes = 'EECS 281|EECS 388';
//        $profile->experiences = 'U of M HCI Lab (Summer 2017)';
        $profile->save();

        // Create faculty user
        $prof = new User();
        $prof->name = 'anishii';
        $prof->email = 'anishii@umich.edu';
        $prof->password = bcrypt('password');
//        $prof->login_method_id = 1;
        $prof->is_student = false;
        $prof->is_faculty = true;
        $prof->save();

        $profile = new Faculty();
        $profile->user_id = $prof->id;
        $profile->first_name = "Akira";
        $profile->last_name = "Nishii";
        $profile->title = "MD, PhD";
        $profile->contact_email = "anishii@umich.edu";

        $profile->save();

        // Create additional faculty
        $prof = new User();
        $prof->name = 'perch_faculty';
        $prof->email = 'faculty@perch.com';
        $prof->password = bcrypt('test');
//        $prof->login_method_id = 1;
        $prof->is_student = false;
        $prof->is_faculty = true;
        $prof->save();

        $profile = new Faculty();
        $profile->user_id = $prof->id;
        $profile->first_name = "Perch";
        $profile->last_name = "Faculty";
        $profile->title = "Graduate Researcher";
        $profile->contact_email = "faculty@perch.com";
        $profile->save();
    }
}
