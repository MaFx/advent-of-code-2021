<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

class Day4 extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'day-4';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'https://adventofcode.com/2021/day/4';

    private $empty_row_index = null;
    private $empty_row_direction = null;
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = explode("\n", File::get('data/day4.txt'));
        array_pop($data);
        $called_numbers = explode(',', array_shift($data));
        array_shift($data);

        $boards = collect($data)
            ->filter()
            ->map(fn($row) => explode(' ', str_replace('  ', ' ', trim($row))))
            ->values();

        $vertical_boards = [];
        foreach (range(0, 495, 5) as $i) {
            $vertical_boards[$i] = [$boards[$i][0], $boards[$i + 1][0], $boards[$i + 2][0], $boards[$i + 3][0], $boards[$i + 4][0]];
            $vertical_boards[$i + 1] = [$boards[$i][1], $boards[$i + 1][1], $boards[$i + 2][1], $boards[$i + 3][1], $boards[$i + 4][1]];
            $vertical_boards[$i + 2] = [$boards[$i][2], $boards[$i + 1][2], $boards[$i + 2][2], $boards[$i + 3][2], $boards[$i + 4][2]];
            $vertical_boards[$i + 3] = [$boards[$i][3], $boards[$i + 1][3], $boards[$i + 2][3], $boards[$i + 3][3], $boards[$i + 4][3]];
            $vertical_boards[$i + 4] = [$boards[$i][4], $boards[$i + 1][4], $boards[$i + 2][4], $boards[$i + 3][4], $boards[$i + 4][4]];
        }

        $vertical_boards = collect($vertical_boards);


//        $this->part1($called_numbers, $boards, $vertical_boards);
        $this->part2($called_numbers, $boards, $vertical_boards);
    }


    private function part1(array $called_numbers, Collection $rows, Collection $vertical_rows)
    {
        $check = [
            array_shift($called_numbers),
            array_shift($called_numbers),
            array_shift($called_numbers),
            array_shift($called_numbers),
            array_shift($called_numbers),
        ];
        do {
            $rows = $rows->map(function ($row, $index) use ($check) {
                $new_row = array_diff($row, $check);
                if (empty($new_row)) {
                    $this->empty_row_index = $index;
                    $this->empty_row_direction = 'h';
                }
                return $new_row;
            });

            $vertical_rows = $vertical_rows->map(function ($row, $index) use ($check) {
                $new_row = array_diff($row, $check);
                if (empty($new_row)) {
                    $this->empty_row_index = $index;
                    $this->empty_row_direction = 'v';
                }
                return $new_row;
            });

            if ($this->empty_row_index !== null) {
                $this->calculate($this->empty_row_direction === 'h' ? $rows : $vertical_rows, $check);
            }

            $check = [array_shift($called_numbers)];
        } while (!empty($called_numbers));
    }

    private function calculate(Collection $boards, array $check)
    {
        $offset = $this->empty_row_index % 5;

        $sum = 0;
        for ($i = 0; $i < 5; $i++) {
            $sum += array_sum($boards[$this->empty_row_index - $offset + $i]);
        }

        dd([
            'Check' => $check[0],
            'Cells' => $sum,
            'Result' => $sum * $check[0],
        ]);
    }

    private function part2(array $called_numbers, Collection $rows, Collection $vertical_rows)
    {
        $matched_rows = [];
        $check = [
            array_shift($called_numbers),
            array_shift($called_numbers),
            array_shift($called_numbers),
            array_shift($called_numbers),
            array_shift($called_numbers),
        ];
        do {
            foreach ($rows as $index => $row) {
                if (in_array($index, $matched_rows)) {
                    continue;
                }
                $new_row = array_diff($row, $check);
                if (empty($new_row)) {
                    $this->empty_row_index = $index;
                    $offset = $index % 5;
//                    dump($check[0] . ' removes horizontal board ' . ($index - $offset) . ' - ' . ($index - $offset + 4));
                    $matched_rows[] = $index - $offset;
                    $matched_rows[] = $index - $offset + 1;
                    $matched_rows[] = $index - $offset + 2;
                    $matched_rows[] = $index - $offset + 3;
                    $matched_rows[] = $index - $offset + 4;
                }
                $rows[$index] = $new_row;
            }
            if ($rows->count() - count($matched_rows) <= 0) {
                $this->calculate($rows, $check);

            }

            foreach ($vertical_rows as $index => $row) {
                if (in_array($index, $matched_rows)) {
                    continue;
                }
                $new_row = array_diff($row, $check);
                if (empty($new_row)) {
                    $this->empty_row_index = $index;
                    $offset = $index % 5;
//                    dump($check[0] . ' removes vertical board ' . ($index - $offset) . ' - ' . ($index - $offset + 4));
                    $matched_rows[] = $index - $offset;
                    $matched_rows[] = $index - $offset + 1;
                    $matched_rows[] = $index - $offset + 2;
                    $matched_rows[] = $index - $offset + 3;
                    $matched_rows[] = $index - $offset + 4;
                }
                $vertical_rows[$index] = $new_row;
            }

            if ($rows->count() - count($matched_rows) <= 0) {
                $this->calculate($rows, $check);
            }

            $check = [array_shift($called_numbers)];
        } while (!empty($called_numbers));
    }
}
