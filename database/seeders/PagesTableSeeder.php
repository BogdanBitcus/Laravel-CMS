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
            ['parent' => 0, 'position' => 10, 'show' => 1, 'type' => 1, 'url' => '/', 'addr' => '', 'name' => '{}', 'date' => now(), 'img' => '{}', 'text' => '{}', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
