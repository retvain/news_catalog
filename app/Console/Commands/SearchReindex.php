<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Components\News\Models\News;
use App\Components\NewsRubrics\Models\NewsRubric;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Console\Command;

class SearchReindex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "search:reindex";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all articles to Elasticsearch';

    /**
     * @var Client
     */
    private Client $elasticsearch;

    /**
     * Create a new command instance.
     *
     * @return void
     * @throws AuthenticationException
     */
    public function __construct()
    {
        parent::__construct();
        $this->elasticsearch = ClientBuilder::create()
            ->setHosts(['elasticsearch:9200'])
            ->build();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function handle(): void
    {
        $this->info('Indexing all news. This might take a while...');
        foreach (News::cursor() as $news) {
            $this->elasticsearch->index([
                'index' => $news->getSearchIndex(),
                'type' => $news->getSearchType(),
                'id' => $news->getKey(),
                'body' => $news->toSearchArray(),
            ]);
            $this->output->write('');
        }
        foreach (NewsRubric::cursor() as $news) {
            $this->elasticsearch->index([
                'index' => $news->getSearchIndex(),
                'type' => $news->getSearchType(),
                'id' => $news->getKey(),
                'body' => $news->toSearchArray(),
            ]);
            $this->output->write('');
        }
        $this->info('Done!');
    }

}
