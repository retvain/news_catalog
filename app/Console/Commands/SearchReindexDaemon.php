<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Common\Services\Search;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Console\Command;

class SearchReindexDaemon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "search:reindex-daemon";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all articles to Elasticsearch';

    /**
     * @var Search
     */
    private $search;

    /**
     * Create a new command instance.
     *
     * @return void
     * @throws AuthenticationException
     */
    public function __construct(Search $search)
    {
        parent::__construct();
        $this->search = $search;

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
        $this->info('Reindex service has ben started.');
        while (true) {
            try {
                $this->search->refreshIndexes();
            } catch (\Throwable $e) {

            }
            sleep(1);
        }
    }

}
