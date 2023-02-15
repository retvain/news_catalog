<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NewsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function smoke(): void
    {
        $response = $this->get('/api/news/rubrics');
        $response->assertStatus(200);

        $response = $this->post('/api/news/rubrics', ['data' => ['rubric_name' => 'test']]);
        $response->assertStatus(200);

        $response = $this->get('/api/news/rubrics/1');
        $response->assertStatus(200);

        $response = $this->delete('/api/news/rubrics/1');
        $response->assertStatus(200);
    }
}
