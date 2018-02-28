<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Student;

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
        $student->save();

        // Create student profile object for student
        $profile = new Student();
        $profile->user_id = $student->id;
        $profile->first_name = 'Akshay';
        $profile->last_name = 'Rao';
        $profile->major = 'Physics';
        $profile->year = 'Junior';
        $profile->gpa = '4.0';
        $profile->save();

        // Create additional users
        $student = new User();
        $student->name = 'perch';
        $student->email = 'test@perch.com';
        $student->password = bcrypt('test');
        $student->save();

        $profile = new Student();
        $profile->user_id = $student->id;
        $profile->first_name = 'Perch';
        $profile->last_name = 'User';
        $profile->year = 'Freshman';
        $profile->major = 'Biology';
        $profile->save();
    }
}
