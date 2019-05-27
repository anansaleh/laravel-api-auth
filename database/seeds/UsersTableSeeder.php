<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Test User1',
            'email' => 'user1@gmail.com',
            'password' => bcrypt('123456'),
            'remember_token' => str_random(10),
        ]);
        DB::table('users')->insert([
            'name' => 'Test User2',
            'email' => 'user2@gmail.com',
            'password' => bcrypt('123456'),
            'remember_token' => str_random(10),
        ]);
    }
}
