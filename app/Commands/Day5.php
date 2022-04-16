<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

class Day5 extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'day-5';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'https://adventofcode.com/2021/day/5';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $data = explode("\n", File::get('data/day5.txt'));
        array_pop($data);

        $lines = collect($data)
            ->map(function($row) {
                return explode(",", str_replace(' -> ', ',', $row));
            });

        $points = collect([]);
        // Consider only horizontal lines
        $lines->filter(fn($line) => $line[0] == $line[2] || $line[1] == $line[3])
            ->each(fn($line) => $this->addLinePoints($line, $points));

        $overlaps = $points
            ->filter(fn ($point) => $point > 1)
            ->count();

        dump('P1 Overlaps: ' . $overlaps);

        $lines->reject(fn($line) => $line[0] == $line[2] || $line[1] == $line[3])
            ->each(fn($line) => $this->addDiagonalLines($line, $points));

        // Comes up as wrong - too much, yet everything is as it should be

        $overlaps = $points
            ->filter(fn ($point) => $point > 1)
            ->count();

        dump('P2 Overlaps: ' . $overlaps);
    }

    private function addLinePoints(array $line, Collection $points)
    {
        if ($line[0] == $line[2]) {
            $direction = $line[1] < $line[3] ? 1 : -1;
            foreach (range($line[1], $line[3], $direction) as $index) {
                $points->put($line[0] . ',' . $index, ($points[$line[0] . ',' . $index] ?? 0) + 1);
            }
        } else {
            $direction = $line[0] < $line[2] ? 1 : -1;
            foreach (range($line[0], $line[2], $direction) as $index) {
                $points->put($index . ',' . $line[1], ($points[$index . ',' .$line[1]] ?? 0) + 1);
            }
        }
    }

    private function addDiagonalLines(array $line, Collection $points)
    {
        $min_x = min($line[0], $line[2]);
        $max_x = max($line[0], $line[2]);
        $min_y = min($line[1], $line[3]);
        for ($i = 0; $i < $max_x - $min_x; $i++) {
            $coordinate = ($min_x + $i) . ',' . ($min_y + $i);
            $points->put($coordinate, ($points[$coordinate] ?? 0) + 1);
        }
    }
}
