<?php

declare(strict_types=1);

namespace App\Components\NewsRubrics\BusinessLayer;

use App\Common\Interfaces\CreateRecordInterface;
use App\Common\Interfaces\UpdateRecordInterface;
use App\Components\NewsRubrics\Models\NewsRubric;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateNewsRubric implements UpdateRecordInterface
{
    /**
     * @var ReadNewsRubric
     */
    private ReadNewsRubric $readNewsRubric;

    public function __construct(ReadNewsRubric $readNewsRubric)
    {
        $this->readNewsRubric = $readNewsRubric;
    }

    /**
     * @throws Exception
     * @throws Throwable
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

        try {
            DB::beginTransaction();
            $newsRubric->update($data);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }


        $newRecord = NewsRubric::find($id);

        return $this->readNewsRubric->one((string)$newRecord->id);
    }
}
