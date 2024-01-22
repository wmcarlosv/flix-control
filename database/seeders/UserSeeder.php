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
                'name'=>'Super Admin',
                'email'=>'super_admin@gmail.com',
                'password'=>bcrypt('SuperAdmin123*'),
                'role'=>'super_admin'
            ],
            [
                'name'=>'admin',
                'email'=>'admin@gmail.com',
                'password'=>bcrypt('Admin123*'),
                'role'=>'admin'
            ]
        ]);
    }
}
