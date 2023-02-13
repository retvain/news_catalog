<?php

declare(strict_types=1);

namespace App\Components\News\BusinessLayer;

use App\Common\Interfaces\CreateRecordInterface;
use App\Common\Interfaces\UpdateRecordInterface;
use App\Components\NewsRubrics\Models\NewsRubric;
use Exception;

class UpdateNews implements UpdateRecordInterface
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
    public function one(array $data, int $id): array
    {
        if (array_key_exists('parent_id', $data) && $data['parent_id'] !== null) {
            $parentId = $data['parent_id'];
            $parent = NewsRubric::find($parentId);
            if (!($parent instanceof NewsRubric)) {
                throw new Exception("Родительской рубрики с идентификатором $parent не найдено. ");
            }
        }

        $newsRubric = NewsRubric::find($id);

        if (!($newsRubric instanceof NewsRubric)) {
            throw new Exception("Ошибка обновления. Рубрика с id $id не найдена. ");
        }

        $newsRubric->update($data);

        $newRecord = NewsRubric::find($id);

        return $this->readNewsRubric->one((string)$newRecord->id);
    }
}
