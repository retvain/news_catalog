<?php

if (isset($router)) {
    $router->group(
        [
            'prefix' => '/api/news',
            'namespace' => '\App\Components\News\Controllers',
        ],
        function () use ($router) {
            $router->get('/', ['uses' => 'NewsController@getRecords']);
            $router->get('/{id}', ['uses' => 'NewsController@getRecord']);
//            $router->post('/', [
//                'uses' => 'NewsController@createRecord',
//                'middleware' => 'createNewsValidationMiddleware'
//            ]);
//            $router->put('/{id}', [
//                'uses' => 'NewsController@updateRecord',
//                'middleware' => 'updateNewsValidationMiddleware'
//            ]);
            $router->delete('/{id}', ['uses' => 'NewsController@deleteRecord']);
        }
    );
}
