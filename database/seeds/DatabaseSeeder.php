<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SkillsTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
//        $this->call(LabsTableSeeder::class);
//        $this->call(LabConnectionsSeeder::class);
//        $this->call(StudentConnectionsSeeder::class);
        $this->call(UROPSeeder::class);
        $this->call(LoginMethodsTableSeeder::class);
    }
}
