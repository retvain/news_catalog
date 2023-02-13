<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Components\NewsRubrics\Models\NewsRubric;
use Illuminate\Database\Seeder;

class NewsRubricsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $records = [
            1 => [
                'id' => 1,
                'rubric_name' => 'Без рубрики'
            ],
            2 => [
                'id' => 2,
                'rubric_name' => 'Город'
            ],
            3 => [
                'id' => 3,
                'rubric_name' => 'Городские происшествия',
                'parent_id' => 2
            ],
        ];

        foreach ($records as $record) {
            NewsRubric::create($record);
        }
    }
}
