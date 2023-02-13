<?php

declare(strict_types=1);

namespace App\Components\News\BusinessLayer\Services;

use Illuminate\Support\Facades\Validator;

class NewsValidator
{
    /**
     * Validate rubrics field
     *
     * @param array $data
     * @return array
     */
    public static function validate(array $data): array
    {
        $validateRules = [
            'news_header' => 'required|max:1000',
            'news_announcement' => 'required',
            'news_body' => 'required'
        ];

        $validateMessages = [
            'news_header.required' => 'Заголовок новости обязателен для заполнения',
            'news_header.max' => 'Заголовок новости превышает 1000 символов',
            'news_announcement.required' => 'Анонс новости обязателен для заполнения',
            'news_body.required' => 'Содержимое новости обязательно для заполнения',
        ];
        $validator = Validator::make($data, $validateRules, $validateMessages);

        return $validator->fails() ? $validator->errors()->all() : [];
    }
}
