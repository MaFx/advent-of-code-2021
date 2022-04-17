<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

class Day6 extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'day-6';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'https://adventofcode.com/2021/day/6';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $data = explode(",", trim(File::get('data/day6.txt')));
        $colony_size = collect($data)
            ->map(fn($index) => $this->fastGrowthRate($index))
            ->sum();

        dd('Colony Size: ' . $colony_size);
    }

    private function slowGrowthRate(array $colony, $days_to_calculate = 255)
    {
        foreach ($colony as $index => $item) {
            if ($item > 0) {
                $colony[$index]--;
            } else {
                $colony[$index] = 6;
                $colony[] = 8;
            }
        }

        if ($days_to_calculate > 0) {
            $colony = $this->growthRate($colony, $days_to_calculate - 1);
        }
        return $colony;
    }

    private function fastGrowthRate(int $days_until_next_spawn, int $last_day = 255)
    {
        // original
        $spawns = 1;
        $spawns_today = [
            $days_until_next_spawn => 1,
        ];

        for ($i = $days_until_next_spawn; $i <= $last_day; $i++) {
            if (!empty($spawns_today[$i])) {
                $spawns_today[$i + 9] = ($spawns_today[$i + 9]  ?? 0) + $spawns_today[$i];
                $spawns_today[$i + 7] = ($spawns_today[$i + 7]  ?? 0) + $spawns_today[$i];
                $spawns += $spawns_today[$i];
            }

        }
        return $spawns;
    }
}
