<?php

declare(strict_types=1);

namespace App\Common\Services;

use App\Components\News\Models\News;
use App\Components\NewsRubrics\Models\NewsRubric;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;

class Search
{
    /**
     * @var Client
     */
    private Client $searcher;

    public function __construct(Client $searcher)
    {
        $this->searcher = $searcher;
    }

    public function getSearcher(): Client
    {
        return $this->searcher;
    }

    /**
     * @return void
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function refreshIndexes(): void
    {
        foreach (News::cursor() as $news) {
            $this->searcher->index([
                'index' => $news->getSearchIndex(),
                'type' => $news->getSearchType(),
                'id' => $news->getKey(),
                'body' => $news->toSearchArray(),
            ]);
        }
        foreach (NewsRubric::cursor() as $news) {
            $this->searcher->index([
                'index' => $news->getSearchIndex(),
                'type' => $news->getSearchType(),
                'id' => $news->getKey(),
                'body' => $news->toSearchArray(),
            ]);
        }
    }
}
