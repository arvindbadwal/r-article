<?php

namespace Cactus\Article\Repositories\Eloquent;

use Cactus\Article\Repositories\ArticleFeedbackInterface;

class ArticleFeedbackRepository extends BaseEloquentRepository implements ArticleFeedbackInterface
{
    /**
     * @var
     */
    protected $model;

    /**
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    public function updateOrCreateFeedback($params, $version = 'new')
    {
        if ($version == 'new') {
            return $this->model->updateOrCreate([
                'user_id' => $params['user_id'],
                'new_article_id' => $params['article_id']
            ], [
                'liked' => $params['liked'],
                'reason' => $params['reason']
            ]);
        }

        return $this->model->updateOrCreate([
            'user_id' => $params['user_id'],
            'article_id' => $params['article_id']
        ], [
            'liked' => $params['liked'],
            'reason' => $params['reason']
        ]);
    }
}