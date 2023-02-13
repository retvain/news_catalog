<?php

declare(strict_types=1);

namespace App\Components\News\BusinessLayer;

use App\Common\Interfaces\CreateRecordInterface;
use App\Components\NewsRubrics\Models\NewsRubric;
use Exception;

class CreateNews implements CreateRecordInterface
{
    /**
     * @var ReadNews
     */
    private ReadNews $readNewsRubric;

    public function __construct(ReadNews $readNewsRubric)
    {
        $this->readNewsRubric = $readNewsRubric;
    }

    /**
     * @throws Exception
     */
    public function one(array $data): array
    {
        if (array_key_exists('parent_id', $data) && $data['parent_id'] !== null) {
            $parentId = $data['parent_id'];
            $parent = NewsRubric::find($parentId);
            if (!($parent instanceof NewsRubric)) {
                throw new Exception("Родительской рубрики с идентификатором $parent не найдено. ");
            }
        }

        $newsRubric = NewsRubric::create([
            'parent_id' => $data['parent_id'] ?? null,
            'rubric_name' => $data['rubric_name']
        ]);

        return $this->readNewsRubric->one((string)$newsRubric->id);
    }
}
