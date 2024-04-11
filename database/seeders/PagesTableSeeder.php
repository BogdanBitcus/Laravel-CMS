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
                ['parent' => 0, 'position' => 10, 'show' => '0', 'template' => 1, 'url' => '', 'addr' => '/', 'name' => 'Home page', 'img' => '', 'text' => 'Some content', 'created_at' => now(), 'updated_at' => now()],
                ['parent' => 1, 'position' => 20, 'show' => '1', 'template' => 2, 'url' => 'about', 'addr' => 'about/', 'name' => 'About us', 'img' => '', 'text' => 'Content about us', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
