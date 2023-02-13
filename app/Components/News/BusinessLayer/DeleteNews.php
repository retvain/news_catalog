<?php

declare(strict_types=1);

namespace App\Components\News\BusinessLayer;

use App\Common\Interfaces\DeleteRecordInterface;
use App\Components\News\Models\News;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class DeleteNews implements DeleteRecordInterface
{
    /**
     * @throws Exception
     * @throws Throwable
     */
    public function one(int $id): void
    {
        $news = News::find($id);

        if (!($news instanceof News)) {
            throw new Exception("Ошибка удаления. Новости с id $id не найдено. ");
        }

        try {
            DB::beginTransaction();
            $consolidated = $news->consolidatedRubricNews;
            if ($consolidated->count() > 0) {
                foreach ($consolidated as $record) {
                    $record->delete();
                }
            }

            $news->delete();
            DB::commit();
        }catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
