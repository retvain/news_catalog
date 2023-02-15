<?php

declare(strict_types=1);

namespace App\Components\NewsRubrics\Controllers;

use App\Common\BaseClasses\BaseCrudController;
use App\Common\Interfaces\CreateRecordInterface;
use App\Common\Interfaces\DeleteRecordInterface;
use App\Common\Interfaces\ReadRecordInterface;
use App\Common\Interfaces\UpdateRecordInterface;
use App\Common\Resources\ErrorResource;
use App\Common\Resources\SuccessResource;
use App\Common\Resources\SuccessResourceCollection;
use App\Components\NewsRubrics\BusinessLayer\Services\NewsRubricSearchService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class NewsRubricController extends BaseCrudController
{
    /**
     * @var CreateRecordInterface
     */
    private CreateRecordInterface $createNewsRubric;

    /**
     * @var UpdateRecordInterface
     */
    private UpdateRecordInterface $updateNewsRubric;

    /**
     * @var DeleteRecordInterface
     */
    private DeleteRecordInterface $deleteNewsRubric;

    /**
     * @var ReadRecordInterface
     */
    private ReadRecordInterface $readNewsRubric;

    /**
     * @var NewsRubricSearchService
     */
    private NewsRubricSearchService $newsRubricsSearchService;

    public function __construct(
        CreateRecordInterface   $createNewsRubric,
        UpdateRecordInterface   $updateNewsRubric,
        DeleteRecordInterface   $deleteNewsRubric,
        ReadRecordInterface     $readNewsRubric,
        NewsRubricSearchService $newsRubricSearchService,
    )
    {
        $this->createNewsRubric = $createNewsRubric;
        $this->updateNewsRubric = $updateNewsRubric;
        $this->deleteNewsRubric = $deleteNewsRubric;
        $this->readNewsRubric = $readNewsRubric;
        $this->newsRubricsSearchService = $newsRubricSearchService;
    }

    /**
     * Get all records
     *
     * @param Request $request
     * @return SuccessResourceCollection|ErrorResource
     */
    public function getRecords(Request $request): SuccessResourceCollection|ErrorResource
    {
        try {
            $params = $this->getParams($request);
            $records = $this->readNewsRubric->all($params);
            $count = $this->readNewsRubric->count($params);
            $result = new SuccessResourceCollection($records, $count);
        } catch (Throwable $e) {
            $result = new ErrorResource(
                ['error' => $e->getMessage()],
                'Ошибка получения списка записей');
        }

        return $result;
    }

    /**
     * Get one record
     *
     * @param string $id
     * @return SuccessResource|ErrorResource
     */
    public function getRecord(string $id): SuccessResource|ErrorResource
    {
        try {
            $records = $this->readNewsRubric->one($id);
            $result = new SuccessResource($records);
        } catch (Throwable $e) {
            $result = new ErrorResource(
                ['error' => $e->getMessage()],
                'Ошибка получения записи');
        }

        return $result;
    }

    /**
     * @param Request $request
     * @return ErrorResource|SuccessResourceCollection
     */
    public function search(Request $request): SuccessResourceCollection|ErrorResource
    {
        try {
            $data = $request->get('data');
            $searchResult = $this->newsRubricsSearchService->findNews($data);
            $result = new SuccessResourceCollection($searchResult);
        } catch (Throwable $e) {
            $result = new ErrorResource(
                ['error' => $e->getMessage()],
                'Ошибка поиска');
        }

        return $result;
    }

    /**
     * Create new record.
     *
     * @param Request $request
     * @return Response|SuccessResource|ErrorResource
     */
    public function createRecord(Request $request): Response|SuccessResource|ErrorResource
    {
        try {
            $data = $request->get('data');
            $record = $this->createNewsRubric->one($data);
            $result = new SuccessResource($record);
        } catch (Throwable $e) {
            $result = new ErrorResource(
                ['error' => $e->getMessage()],
                'Ошибка создания записи');
        }

        return $result;
    }

    /**
     * Update one record.
     *
     * @param Request $request
     * @param string $id
     * @return Response|SuccessResource|ErrorResource
     */
    public function updateRecord(Request $request, string $id): Response|SuccessResource|ErrorResource
    {
        try {
            $data = $request->get('data');
            $record = $this->updateNewsRubric->one($data, (int)$id);
            $result = new SuccessResource($record);
        } catch (Throwable $e) {
            $result = new ErrorResource(
                ['error' => $e->getMessage()],
                'Ошибка обновления записи');
        }

        return $result;
    }

    /**
     * Delete one record.
     *
     * @param string $id
     * @return Response|SuccessResource|ErrorResource
     */
    public function deleteRecord(string $id): Response|SuccessResource|ErrorResource
    {
        try {
            $this->deleteNewsRubric->one((int)$id);
            $result = new SuccessResource();
        } catch (Throwable $e) {
            $result = new ErrorResource(
                ['error' => $e->getMessage()],
                'Ошибка обновления записи');
        }

        return $result;
    }
}
