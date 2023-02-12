<?php

declare(strict_types=1);

namespace App\Providers;

use App\Common\Interfaces\CreateRecordInterface;
use App\Common\Interfaces\DeleteRecordInterface;
use App\Common\Interfaces\ReadRecordInterface;
use App\Common\Interfaces\UpdateRecordInterface;
use App\Components\NewsRubrics\BusinessLayer\CreateNewsRubric;
use App\Components\NewsRubrics\BusinessLayer\DeleteNewsRubric;
use App\Components\NewsRubrics\BusinessLayer\ReadNewsRubric;
use App\Components\NewsRubrics\BusinessLayer\UpdateNewsRubric;
use App\Components\NewsRubrics\Controllers\NewsRubricController;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
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
    }
}
