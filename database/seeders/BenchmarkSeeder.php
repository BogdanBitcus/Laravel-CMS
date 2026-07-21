<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Pages;

class BenchmarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $count = 20000;
        $start = microtime(true);
        $this->command->getOutput()->progressStart($count);
        $startMemory = memory_get_usage(true);

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Pages::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $root = Pages::create([
            'parent' => 0,
            'slug' => 'home',
            'addr' => 'home',
            'name' => 'Home',
        ]);
        $parents=[$root->id];
        $id = $root->id + 1;

        while($id<=$count){

            $parent=array_shift($parents);

            for($i=0;$i<10 && $id<=$count;$i++){

                /*Pages::create([
                    'parent'=>$parent,
                    'slug'=>'page'.$id,
                    'addr'=>''
                ]);*/

                $parents[]=$id;
                $id++;
                $this->command->getOutput()->progressAdvance();
            }
        }
        $this->command->getOutput()->progressFinish();
        $this->command->info(
            'Seeder: '.round(microtime(true)-$start,2).' sec'
        );
        $this->command->info(
            'Used: '.round(
                (memory_get_usage(true)-$startMemory)/1024/1024,
                2
            ).' MB'
        );
        $this->command->info(
            'Peak: '.round(
                memory_get_peak_usage(true)/1024/1024,
                2
            ).' MB'
        );
    }
}
