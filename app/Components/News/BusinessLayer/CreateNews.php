<?php

declare(strict_types=1);

namespace App\Components\News\BusinessLayer;

use App\Common\Interfaces\CreateRecordInterface;
use App\Components\News\Models\ConsolidatedRubricNews;
use App\Components\News\Models\News;
use App\Components\NewsRubrics\Models\NewsRubric;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateNews implements CreateRecordInterface
{
    /**
     * @var ReadNews
     */
    private ReadNews $readNews;

    public function __construct(ReadNews $readNews)
    {
        $this->readNews = $readNews;
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function one(array $data): array
    {
        unset($data['id']);
        try {
            DB::beginTransaction();
            $news = News::create($data);

            if (array_key_exists('rubrics_id', $data) && count($data['rubrics_id']) > 0) {
                foreach ($data['rubrics_id'] as $rubricId) {
                    $rubric = NewsRubric::find($rubricId);
                    if (!($rubric instanceof NewsRubric)) {
                        throw new Exception("Рубрики с идентификатором $rubricId не существует.");
                    }
                    ConsolidatedRubricNews::create([
                        'news_id' => $news->id,
                        'news_rubric_id' => $rubricId
                    ]);
                }
            } else {
                ConsolidatedRubricNews::create([
                    'news_id' => $news->id,
                    'news_rubric_id' => NewsRubric::NO_RUBRIC_ID
                ]);
            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }


        return $this->readNews->one((string)$news->id);
    }
}
