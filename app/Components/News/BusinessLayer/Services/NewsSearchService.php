<?php

declare(strict_types=1);

namespace App\Components\News\BusinessLayer\Services;

use App\Common\Services\Search;
use App\Components\News\Models\News;
use Elastic\Elasticsearch\Client;
use Exception;
use Throwable;

class NewsSearchService
{
    /**
     * @var Client
     */
    private Client $client;

    public function __construct(Search $search)
    {
        $this->client = $search->getSearcher();
    }

    /**
     * @throws Exception
     */
    public function findNews(array $data): array
    {
        $query = $data['search'] ?? throw new Exception("Не найдены данные для поиска");
        $model = new News;
        try {
            $items = $this->client->search([
                'index' => $model->getSearchIndex(),
                'type' => $model->getSearchType(),
                'body' => [
                    'query' => [
                        'multi_match' => [
                            'fields' => ['news_body', 'news_header', 'news_announcement'],
                            'query' => $query,
                        ],
                    ],
                ],
            ]);
        } catch (Throwable $e) {
            throw new Exception("Не удалось выполнить поиск. Причина: " . $e->getMessage());
        }

        $resultArr = $items->asArray();
        $hits = $resultArr["hits"]["hits"] ?? throw new Exception("Искомая фраза не найдена");
        $result = [];
        foreach ($hits as $hit) {
            $result[] = $hit['_source'];
        }

        return $result;
    }
}
