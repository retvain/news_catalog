<?php

declare(strict_types=1);

namespace App\Components\NewsRubrics\BusinessLayer;

use App\Common\Interfaces\CreateRecordInterface;
use App\Components\NewsRubrics\Models\NewsRubric;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateNewsRubric implements CreateRecordInterface
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
    public function one(array $data): array
    {
        if (array_key_exists('parent_id', $data) && $data['parent_id'] !== null) {
            $parentId = $data['parent_id'];
            $parent = NewsRubric::find($parentId);
            if (!($parent instanceof NewsRubric)) {
                throw new Exception("Родительской рубрики с идентификатором $parent не найдено. ");
            }
        }

        try {
            DB::beginTransaction();
            $newsRubric = NewsRubric::create([
                'parent_id' => $data['parent_id'] ?? null,
                'rubric_name' => $data['rubric_name']
            ]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return $this->readNewsRubric->one((string)$newsRubric->id);
    }
}
