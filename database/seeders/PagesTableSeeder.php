<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pages')->insert([
                ['parent' => 0, 'position' => 10, 'published' => '0', 'template' => 1, 'slug' => '', 'addr' => '', 'name' => 'Home page', 'content' => 'Some content', 'created_at' => now(), 'updated_at' => now()],
                ['parent' => 1, 'position' => 20, 'published' => '1', 'template' => 2, 'slug' => 'about', 'addr' => 'about', 'name' => 'About us', 'content' => 'Content about us', 'created_at' => now(), 'updated_at' => now()],
                ['parent' => 1, 'position' => 30, 'published' => '1', 'template' => 3, 'slug' => 'news', 'addr' => 'news', 'name' => 'News', 'content' => '', 'created_at' => now(), 'updated_at' => now()],
                ['parent' => 1, 'position' => 40, 'published' => '1', 'template' => 5, 'slug' => 'contacts', 'addr' => 'contacts', 'name' => 'Contacts', 'content' => '', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
