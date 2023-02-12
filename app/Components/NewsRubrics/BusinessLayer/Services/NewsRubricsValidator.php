<?php

declare(strict_types=1);

namespace App\Components\NewsRubrics\BusinessLayer\Services;

use Illuminate\Support\Facades\Validator;

class NewsRubricsValidator
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
            'parent_id' => 'integer',
            'rubric_name' => 'required|max:200|unique:news_rubrics',
        ];

        $validateMessages = [
            'parent_id.integer' => 'Идентификатор родительской рубрики должен быть числом',
            'rubric_name.required' => 'Наименование рубрики обязательно для заполнения',
            'rubric_name.max' => 'Наименование рубрики превышает 200 символов',
            'rubric_name.unique' => 'Такое наименование рубрики уже имеется',
        ];
        $validator = Validator::make($data, $validateRules, $validateMessages);

        return $validator->fails() ? $validator->errors()->all() : [];
    }
}
