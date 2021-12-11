<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

class DayOne extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'day-one';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'https://adventofcode.com/2021/day/1';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = explode("\n", File::get('data/day1-input.txt'));
        array_pop($data);

        $this->part1($data);
        $this->part2($data);
    }

    /**
     * Define the command's schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
    }

    private function part1(array $data)
    {
        $inc = 0;

        $previous = array_shift($data);

        do {
            $value = array_shift($data);

            if ($previous < $value) {
                $inc++;
            }
            $previous = $value;
        } while (!empty($data));

        dump('Part 1 increase count: ' . $inc);
    }

    private function part2(array $data)
    {
        $inc = 0;

        $previous = $data[0] + $data[1] + $data[2];

        for ($i = 1; $i < count($data) - 2; $i++) {
            $value = $data[$i] + $data[$i + 1] + $data[$i + 2];

            if ($previous < $value) {
                $inc++;
            }
            $previous = $value;
        }

        dump('Part 2 increase count: ' . $inc);
    }
}
