<?php

namespace App\Console\Commands;

use App\Helpers\CmsHelper;
use App\Models\Pages;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class BenchmarkPages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'benchmark:pages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Benchmark rebuildAddr';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Start rebuild...');

        DB::enableQueryLog();

        $count = Pages::count();
        $this->info("Pages: {$count}");

        $startMemory = memory_get_usage(true);
        $start = microtime(true);

        //CmsHelper::rebuildAddr([1]);

        $time = microtime(true) - $start;
        $memory = memory_get_usage(true) - $startMemory;

        $this->info('Time: '.round($time,3).' sec');
        $this->info('Memory: '.round($memory/1024/1024,2).' MB');
        $this->info('Queries: '.count(DB::getQueryLog()));

        $this->info(
            'Peak after rebuild: '
            .round(memory_get_peak_usage(true)/1024/1024,2)
            .' MB'
        );

        $this->table(
            ['Metric', 'Value'],
            [
                ['Time', round($time,2).' sec'],
                ['Queries', count(DB::getQueryLog())],
                ['Memory', round($memory/1024/1024,2).' MB'],
                ['Peak', round(memory_get_peak_usage(true)/1024/1024,2).' MB'],
            ]
        );
    }
}
