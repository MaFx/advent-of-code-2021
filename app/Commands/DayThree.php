<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

class DayThree extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'day-three';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'https://adventofcode.com/2021/day/3';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = explode("\n", File::get('data/day3.txt'));
        array_pop($data);

        $this->part1($data);
        $this->part2($data);
    }


    private function part1(array $data)
    {
        $numbers = array_fill(0, 12, 0);

        foreach ($data as $row) {
            foreach (range(0, strlen($row) -1) as $i) {
                $numbers[$i] += $row[$i] === '1' ? 1 : 0;
            }
        }

        $gamma = $epsilon = '';
        $half = count($data) / 2;

        foreach ($numbers as $digit) {
            $gamma .= $digit > $half ? '1' : '0';
            $epsilon .= $digit < $half ? '1' : '0';
        }

        dump("P1 Gamma: $gamma, Epsilon: $epsilon, Power: " . bindec($gamma) * bindec($epsilon));
    }

    private function part2(array $data)
    {
        $oxygen = $this->oxygenValue($data);
        $co2 = $this->co2Value($data);

        dump("P2 O2: $oxygen, CO2: $co2, Life Support   " . bindec($oxygen) * bindec($co2));
    }

    private function oxygenValue(array $data): string
    {
        $max_iterations = strlen($data[0]);
        foreach (range(0, $max_iterations) as $index) {
            $majority = $this->majorityValue($data, $index);
            $data = array_filter($data, fn($entry) => $entry[$index] == $majority);
            if (count($data) == 1) {
                break;
            }
        }

        return array_pop($data);
    }

    private function co2Value(array $data): string
    {
        $max_iterations = strlen($data[0]);
        foreach (range(0, $max_iterations) as $index) {
            $minority = $this->majorityValue($data, $index) ? 0 : 1; // invert
            $data = array_filter($data, fn($entry) => $entry[$index] == $minority);
            if (count($data) == 1) {
                break;
            }
        }

        return array_pop($data);
    }

    private function majorityValue($data, $index)
    {
        $sum = 0;
        foreach ($data as $entry) {
            $sum += $entry[$index] === '1' ? 1 : 0;
        }

        return $sum >= count($data) / 2 ? 1 : 0;
    }
}
