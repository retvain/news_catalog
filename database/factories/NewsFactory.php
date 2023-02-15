<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Components\News\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'news_header' => $this->faker->sentence,
            'news_announcement' => $this->faker->text(100),
            'news_body' => $this->faker->text(1000),
        ];
    }
}
