<?php

if (isset($router)) {
    $router->group(
        [
            'prefix' => '/api/news/rubrics',
            'namespace' => '\App\Components\NewsRubrics\Controllers',
        ],
        function () use ($router) {
            $router->get('/', ['uses' => 'NewsRubricController@getRecords']);
            $router->get('/parents', ['uses' => 'NewsRubricController@getParents']);
            $router->get('/{id}', ['uses' => 'NewsRubricController@getRecord']);
            $router->get('/{parentId}/children', ['uses' => 'NewsRubricController@getAllByParent']);
            $router->post('/', [
                'uses' => 'NewsRubricController@createRecord',
                'middleware' => 'createNewsRubricsValidationMiddleware'
            ]);
            $router->post('/search', ['uses' => 'NewsRubricController@search']);
            $router->put('/{id}', [
                'uses' => 'NewsRubricController@updateRecord',
                'middleware' => 'updateNewsRubricsValidationMiddleware'
            ]);
            $router->delete('/{id}', ['uses' => 'NewsRubricController@deleteRecord']);
        }
    );
}
