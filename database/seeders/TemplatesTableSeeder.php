<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('templates')->insert([
            ['name' => 'Home page', 'view_tpl' => 'view_home', 'admin_tpl' => 'edit_list', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'About us', 'view_tpl' => 'view_about', 'admin_tpl' => 'edit_html', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'News list', 'view_tpl' => 'view_news', 'admin_tpl' => 'edit_list', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'New', 'view_tpl' => 'view_new', 'admin_tpl' => 'edit_html', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
