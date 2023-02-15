<?php

declare(strict_types=1);

namespace App\Common\Services;

use Elastic\Elasticsearch\Client;

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
}
