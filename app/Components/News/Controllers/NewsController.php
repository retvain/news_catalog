<?php

declare(strict_types=1);

namespace App\Components\News\Controllers;

use App\Common\BaseClasses\BaseCrudController;
use App\Common\Interfaces\CreateRecordInterface;
use App\Common\Interfaces\DeleteRecordInterface;
use App\Common\Interfaces\ReadRecordInterface;
use App\Common\Interfaces\UpdateRecordInterface;
use App\Common\Resources\ErrorResource;
use App\Common\Resources\SuccessResource;
use App\Common\Resources\SuccessResourceCollection;
use App\Components\News\BusinessLayer\Services\NewsSearchService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class NewsController extends BaseCrudController
{
    /**
     * @var CreateRecordInterface
     */
    private CreateRecordInterface $createNews;

    /**
     * @var UpdateRecordInterface
     */
    private UpdateRecordInterface $updateNews;

    /**
     * @var DeleteRecordInterface
     */
    private DeleteRecordInterface $deleteNews;

    /**
     * @var ReadRecordInterface
     */
    private ReadRecordInterface $readNews;

    /**
     * @var NewsSearchService
     */
    private NewsSearchService $newsSearchService;

    public function __construct(
        CreateRecordInterface $createNews,
        UpdateRecordInterface $updateNews,
        DeleteRecordInterface $deleteNews,
        ReadRecordInterface   $readNews,
        NewsSearchService     $newsSearchService
    )
    {
        $this->createNews = $createNews;
        $this->updateNews = $updateNews;
        $this->deleteNews = $deleteNews;
        $this->readNews = $readNews;
        $this->newsSearchService = $newsSearchService;
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
            $records = $this->readNews->all($params);
            $count = $this->readNews->count($params);
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
            $records = $this->readNews->one($id);
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
            $searchResult = $this->newsSearchService->findNews($data);
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
    public
    function createRecord(Request $request): Response|SuccessResource|ErrorResource
    {
        try {
            $data = $request->get('data');
            $record = $this->createNews->one($data);
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
            $record = $this->updateNews->one($data, (int)$id);
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
            $this->deleteNews->one((int)$id);
            $result = new SuccessResource();
        } catch (Throwable $e) {
            $result = new ErrorResource(
                ['error' => $e->getMessage()],
                'Ошибка обновления записи');
        }

        return $result;
    }
}
