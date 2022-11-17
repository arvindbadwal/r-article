<?php

namespace Cactus\Article\Repositories\Eloquent;

use Cactus\Article\Repositories\UserArticleActionInterface;

class UserArticleActionRepository extends BaseEloquentRepository implements UserArticleActionInterface
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

    public function updateOrCreateAction($params, $version = 'new')
    {
        if($version == 'new') {
            return $this->model->updateOrCreate([
                'user_id' => $params['user_id'],
                'new_article_id' => $params['article_id']
            ], [
                'action_performed' => $params['action_performed']
            ]);
        }

        return $this->model->updateOrCreate([
            'user_id' => $params['user_id'],
            'article_id' => $params['article_id']
        ], [
            'action_performed' => $params['action_performed']
        ]);
    }
}