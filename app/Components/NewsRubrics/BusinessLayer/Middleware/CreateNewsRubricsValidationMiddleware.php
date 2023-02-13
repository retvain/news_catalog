<?php

declare(strict_types=1);

namespace App\Components\NewsRubrics\BusinessLayer\Middleware;

use App\Common\Resources\ErrorResource;
use App\Components\NewsRubrics\BusinessLayer\Services\NewsRubricsValidator;
use Closure;
use Illuminate\Http\Request;

class CreateNewsRubricsValidationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $data = $request->get('data');

        $result = NewsRubricsValidator::validate($data);

        if ($result != []) {
            return new ErrorResource(
                $result,
                'Имеются ошибки валидации'
            );
        }

        return $next($request);
    }

}
