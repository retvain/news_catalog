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
        NewsRubric::create(
            [
                'id' => 1,
                'rubric_name' => 'Без рубрики'
            ]
        );
    }
}
