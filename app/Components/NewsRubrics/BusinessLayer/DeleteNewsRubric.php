<?php

declare(strict_types=1);

namespace App\Components\NewsRubrics\BusinessLayer;

use App\Common\Interfaces\DeleteRecordInterface;
use App\Components\NewsRubrics\Models\NewsRubric;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class DeleteNewsRubric implements DeleteRecordInterface
{
    /**
     * @throws Exception
     * @throws Throwable
     */
    public function one(int $id): void
    {
        $newsRubric = NewsRubric::find($id);

        if (!($newsRubric instanceof NewsRubric)) {
            throw new Exception("Ошибка удаления. Рубрика с id $id не найдена. ");
        }
        try {
            DB::beginTransaction();
            $newsRubric->delete();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
