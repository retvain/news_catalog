<?php

declare(strict_types=1);

namespace App\Components\NewsRubrics\BusinessLayer\Middleware;

use App\Common\Resources\ErrorResource;
use App\Components\NewsRubrics\BusinessLayer\Services\NewsRubricsValidator;
use App\Components\NewsRubrics\Models\NewsRubric;
use Closure;
use Illuminate\Http\Request;

class UpdateNewsRubricsValidationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $data = $request->get('data');
        $id = (int)$request->id;
        $dataForUpdate = $this->dataForUpdate($data, $id);

        $result = NewsRubricsValidator::validate($dataForUpdate);

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

        $newsRubric = NewsRubric::find($id);

        if ($newsRubric instanceof NewsRubric) {
            $oldData = [
                'parent_id' => $newsRubric->parent_id,
                'rubric_name' => $newsRubric->rubric_name,
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
