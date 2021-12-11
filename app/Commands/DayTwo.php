<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

class DayTwo extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'day-two';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'https://adventofcode.com/2021/day/2';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = explode("\n", File::get('data/day2-input.txt'));
        array_pop($data);

        $this->part1($data);
        $this->part2($data);
    }


    private function part1(array $data)
    {
        $position = $depth = 0;
        foreach ($data as $entry) {
            if (stripos($entry, 'forward') !== false) {
                $position += str_replace('forward ', '', $entry);
            } elseif (stripos($entry, 'down') !== false) {
                $depth += str_replace('down ', '', $entry);
            } elseif (stripos($entry, 'up') !== false) {
                $depth -= str_replace('up ', '', $entry);
            }
        }

        dump("P1 Pos: $position, Depth: $depth, total: " . $depth * $position);
    }

    private function part2(array $data)
    {
        $position = $depth = $aim = 0;
        foreach ($data as $entry) {
            if (stripos($entry, 'forward') !== false) {
                $position += str_replace('forward ', '', $entry);
                $depth += str_replace('forward ', '', $entry) * $aim;
            } elseif (stripos($entry, 'down') !== false) {
                $aim += str_replace('down ', '', $entry);
            } elseif (stripos($entry, 'up') !== false) {
                $aim -= str_replace('up ', '', $entry);
            }
        }

        dump("P2 Pos: $position, Depth: $depth, total: " . $depth * $position);
    }
}
