<?php

declare(strict_types=1);

namespace App\Components\News\BusinessLayer\Middleware;

use App\Common\Resources\ErrorResource;
use App\Components\News\BusinessLayer\Services\NewsValidator;
use App\Components\News\Models\News;
use Closure;
use Illuminate\Http\Request;

class UpdateNewsValidationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $data = $request->get('data');
        $id = (int)$request->id;
        $dataForUpdate = $this->dataForUpdate($data, $id);

        $result = NewsValidator::validate($dataForUpdate);

        if ($result != []) {
            return new ErrorResource(
                $result,
                'Имеются ошибки валидации'
            );
        }

        return $next($request);
    }

    private function dataForUpdate(array $data, int $id): array
    {
        $dataForUpdate = [];

        $news = News::find($id);

        if ($news instanceof News) {
            $oldData = [
                'news_header' => $news->news_header,
                'news_announcement' => $news->news_announcement,
                'news_body' => $news->news_body,
            ];

            foreach ($data as $k => $field) {
                if (array_key_exists($k, $oldData)) {
                    if ($oldData[$k] != $field) {
                        $dataForUpdate[$k] = $field;
                    }
                }
            }
        }

        return $dataForUpdate;
    }

}
