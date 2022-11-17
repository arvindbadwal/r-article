<?php

use Illuminate\Support\Facades\Route;

Route::group(config('article.route', []), function () {
    Route::group(['namespace' => 'Cactus\Article\Http\Controllers'], function () {
        Route::group(['prefix' => 'articles'], function () {
            Route::get('histories', 'ArticleHistoryController@getArticleHistory');
            Route::post('{article_id}/read', 'ArticleHistoryController@saveArticleRead');

            Route::post('{article_id}/{reaction}', 'ArticleFeedbackController@saveFeedback')
                ->where(['reaction' => 'like|dislike']);

            Route::get('{article_id}', function () {
                return 'Article Meta Details';
            });
        });
    });
});