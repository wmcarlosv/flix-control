<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id'=>1,
                'name'=>'Super Admin',
                'email'=>'super_admin@gmail.com',
                'password'=>bcrypt('SuperAdmin123*'),
                'role'=>'super_admin',
                'date_to'=>null,
                'parent_user_id'=>null
            ],
            [
                'id'=>2,
                'name'=>'admin',
                'email'=>'admin@gmail.com',
                'password'=>bcrypt('Admin123*'),
                'role'=>'admin',
                'date_to'=>'2080/12/31',
                'parent_user_id'=>1
            ]
        ]);
    }
}
