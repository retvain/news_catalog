<?php

declare(strict_types=1);

namespace App\Components\NewsRubrics\BusinessLayer;

use App\Common\Interfaces\ReadRecordInterface;
use App\Common\Lists\RecordsList;
use App\Components\NewsRubrics\Models\NewsRubric;
use Illuminate\Database\Eloquent\Builder;

class ReadNewsRubric implements ReadRecordInterface
{
    /**
     * @return Builder
     */
    private function getBaseQuery(): Builder
    {
        return NewsRubric::query()
            ->select([
                'id',
                'parent_id',
                'rubric_name'
            ]);
    }

    /**
     * @param array $params
     * @return array
     */
    public function all(array $params): array
    {
        $query = new RecordsList($this->getBaseQuery(), $params);

        return $this->collectToArr($query);
    }

    /**
     * @param array $params
     * @return array
     */
    public function allParents(array $params): array
    {
        $query = new RecordsList(
            $this->getBaseQuery()->where('parent_id'),
            $params);


        return $this->collectToArr($query);
    }

    /**
     * @param array $params
     * @param $parentId
     * @return array
     */
    public function allByParentId(array $params, $parentId): array
    {
        $query = new RecordsList(
            $this->getBaseQuery()->where('parent_id', '=', $parentId),
            $params);


        return $this->collectToArr($query);
    }


    /**
     * @param RecordsList $recordsList
     * @return array
     */
    private function collectToArr(RecordsList $recordsList): array
    {
        $records = $recordsList->getRecords();
        $result = $records->toArray();
        $this->addIsHaveChildField($result);
        return $result;
    }

    /**
     * Добавляем признак наличия дочерней рубрики
     *
     * @param array $records
     * @return void
     */
    private function addIsHaveChildField(array &$records): void
    {
        foreach ($records as $k => $record) {
            $records[$k]['have_child'] = false;
            $haveChild = NewsRubric::where('parent_id', $record['id'])->first() instanceof NewsRubric;
            if ($haveChild) {
                $records[$k]['have_child'] = true;
            }
        }
    }

    /**
     * @param string $id
     * @return array
     */
    public function one(string $id): array
    {
        $query = $this->getBaseQuery()
            ->find($id);

        $result = $query->toArray();

        return $result;
    }

    /**
     * @param $params
     * @return int
     */
    public function count($params): int
    {
        $query = new RecordsList(self::getBaseQuery(), $params);

        return $query->countTotal();
    }
}
