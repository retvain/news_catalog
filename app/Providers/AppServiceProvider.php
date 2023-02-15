<?php

declare(strict_types=1);

namespace App\Providers;

use App\Common\Interfaces\CreateRecordInterface;
use App\Common\Interfaces\DeleteRecordInterface;
use App\Common\Interfaces\ReadRecordInterface;
use App\Common\Interfaces\UpdateRecordInterface;
use App\Components\News\BusinessLayer\CreateNews;
use App\Components\News\BusinessLayer\DeleteNews;
use App\Components\News\BusinessLayer\ReadNews;
use App\Components\News\BusinessLayer\UpdateNews;
use App\Components\News\Controllers\NewsController;
use App\Components\NewsRubrics\BusinessLayer\CreateNewsRubric;
use App\Components\NewsRubrics\BusinessLayer\DeleteNewsRubric;
use App\Components\NewsRubrics\BusinessLayer\ReadNewsRubric;
use App\Components\NewsRubrics\BusinessLayer\UpdateNewsRubric;
use App\Components\NewsRubrics\Controllers\NewsRubricController;
use Elastic\Elasticsearch\Client as ElasticSearchClient;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ElasticSearchClient::class, function () {
            return ClientBuilder::create()
                ->setHosts([env('ELASTIC_HOST', 'elasticsearch') . ':' . env('ELASTIC_PORT', '9200')])
                ->build();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // rubrics
        $this->app
            ->when(NewsRubricController::class)
            ->needs(CreateRecordInterface::class)
            ->give(CreateNewsRubric::class);
        $this->app
            ->when(NewsRubricController::class)
            ->needs(ReadRecordInterface::class)
            ->give(ReadNewsRubric::class);
        $this->app
            ->when(NewsRubricController::class)
            ->needs(UpdateRecordInterface::class)
            ->give(UpdateNewsRubric::class);
        $this->app
            ->when(NewsRubricController::class)
            ->needs(DeleteRecordInterface::class)
            ->give(DeleteNewsRubric::class);

        // news
        $this->app
            ->when(NewsController::class)
            ->needs(CreateRecordInterface::class)
            ->give(CreateNews::class);
        $this->app
            ->when(NewsController::class)
            ->needs(ReadRecordInterface::class)
            ->give(ReadNews::class);
        $this->app
            ->when(NewsController::class)
            ->needs(UpdateRecordInterface::class)
            ->give(UpdateNews::class);
        $this->app
            ->when(NewsController::class)
            ->needs(DeleteRecordInterface::class)
            ->give(DeleteNews::class);
    }
}
