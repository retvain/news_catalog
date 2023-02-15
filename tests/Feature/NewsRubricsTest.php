<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class NewsRubricsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function smoke(): void
    {
        $response = $this->get('/api/news');
        $response->assertStatus(200);

        $response = $this->post('/api/news', ['data' => [
            'news_header' => 'новости',
            'news_announcement' => 'Анонс новости',
            'news_body' => 'Текст новости, описание проишествия.',
            'rubrics_id' => [1]
        ]]);
        $response->assertStatus(200);

        $response = $this->get('/api/news/1');
        $response->assertStatus(200);

        $response = $this->delete('/api/news/1');
        $response->assertStatus(200);
    }
}
