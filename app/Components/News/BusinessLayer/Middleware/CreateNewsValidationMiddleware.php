<?php

declare(strict_types=1);

namespace App\Components\News\BusinessLayer\Middleware;

use App\Common\Resources\ErrorResource;
use App\Components\News\BusinessLayer\Services\NewsValidator;
use Closure;
use Illuminate\Http\Request;

class CreateNewsValidationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $data = $request->get('data');

        $result = NewsValidator::validate($data);

        if ($result != []) {
            return new ErrorResource(
                $result,
                'Имеются ошибки валидации'
            );
        }

        return $next($request);
    }

}
