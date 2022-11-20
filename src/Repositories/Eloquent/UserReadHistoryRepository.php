<?php

namespace Cactus\Article\Repositories\Eloquent;

use Cactus\Article\Repositories\UserReadHistoryInterface;

class UserReadHistoryRepository extends BaseEloquentRepository implements UserReadHistoryInterface
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

    public function updateOrCreateRead($params, $version = 'new')
    {
        if ($version == 'new') {
            return $this->model->updateOrCreate([
                'user_id' => $params['user_id'],
                'new_article_id' => $params['article_id']
            ], [
                'read_via' => $params['read_via']
            ]);
        }

        return $this->model->updateOrCreate([
                'user_id' => $params['user_id'],
                'article_id' => $params['article_id']
            ], [
                'read_via' => $params['read_via']
            ]);
    }

    public function updateArticleMetaById($id, $articleMeta)
    {
        return $this->model
            ->whereId($id)
            ->update([
                'article_meta' => $articleMeta,
                'is_active' => true
            ]);
    }
}