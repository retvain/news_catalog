<?php

namespace Database\Seeders;

use App\Components\News\Models\ConsolidatedRubricNews;
use App\Components\News\Models\News;
use App\Components\NewsRubrics\Models\NewsRubric;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $news = News::create([
            'news_header' => 'Заголовок новости',
            'news_announcement' => 'Анонс новости',
            'news_body' => 'Текст новости',
        ]);

        ConsolidatedRubricNews::create([
            'news_id' => $news->id,
            'news_rubric_id' => NewsRubric::NO_RUBRIC_ID,
        ]);
    }
}
