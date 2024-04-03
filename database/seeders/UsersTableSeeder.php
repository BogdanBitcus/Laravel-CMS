<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['name' => 'Admin', 'email' => 'test@example.com', 'password' => '$2y$12$x8k/5u6s9eqDcjQCXHkiqeLcS1AKsC/CVJEJ7ammTKo9humQznHx.', 'is_admin' => '1', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
