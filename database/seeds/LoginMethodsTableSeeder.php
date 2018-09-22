<?php
use Illuminate\Database\Seeder;
use App\LoginMethod;

class LoginMethodsTableSeeder extends Seeder {
    public function run() {
        DB::table('login_methods')->delete();
        DB::table('login_methods')->insert([
            'method' => 'password'
        ]);
        DB::table('login_methods')->insert([
            'method' => 'google'
        ]);
        // DB::table('login_methods')->insert([
        //    'method' => 'facebook'
        // ]);
    }
}