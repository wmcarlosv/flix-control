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
                'name'=>'Administrador',
                'email'=>'administrador@gmail.com',
                'password'=>bcrypt('Admin123*'),
                'role'=>'super_admin',
                'date_to'=>null,
                'parent_user_id'=>null
            ]
        ]);
    }
}
