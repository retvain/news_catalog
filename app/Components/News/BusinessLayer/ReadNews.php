<?php

declare(strict_types=1);

namespace App\Components\News\BusinessLayer;

use App\Common\Interfaces\ReadRecordInterface;
use App\Common\Lists\RecordsList;
use App\Components\News\Models\News;
use Illuminate\Database\Eloquent\Builder;

class ReadNews implements ReadRecordInterface
{
    private function getBaseQuery(): Builder
    {
        return News::query()
            ->select(
                'id',
                'news_header',
                'news_announcement',
                'news_body'
            )->with('rubrics');
    }

    public function all(array $params): array
    {
        $query = new RecordsList($this->getBaseQuery(), $params);
        $records = $query->getRecords();
        $result = $records->toArray();

        return $result;
    }

    public function one(string $id): array
    {
        $query = $this->getBaseQuery()
            ->find($id);

        $result = $query->toArray();

        return $result;
    }

    public function count($params): int
    {
        $query = new RecordsList(self::getBaseQuery(), $params);

        return $query->countTotal();
    }
}
