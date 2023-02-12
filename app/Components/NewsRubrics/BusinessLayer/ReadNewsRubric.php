<?php

declare(strict_types=1);

namespace App\Components\NewsRubrics\BusinessLayer;

use App\Common\Interfaces\ReadRecordInterface;
use App\Common\Lists\RecordsList;
use App\Components\NewsRubrics\Models\NewsRubric;
use Illuminate\Database\Eloquent\Builder;

class ReadNewsRubric implements ReadRecordInterface
{
    private function getBaseQuery(): Builder
    {
        return NewsRubric::query()
            ->select([
                'id',
                'parent_id',
                'rubric_name'
            ]);
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
