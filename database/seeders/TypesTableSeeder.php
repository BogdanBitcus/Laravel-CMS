<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('types')->insert([
            ['name' => 'Home',  'view_tpl' => 'view_home',  'admin_tpl' => 'edit_list', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'About', 'view_tpl' => 'view_about', 'admin_tpl' => 'edit_html', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
