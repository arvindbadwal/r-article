<?php

namespace Cactus\Article\Repositories\Eloquent;

class BaseEloquentRepository
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

    public function updateById($id, $attributes)
    {
        return $this->model
            ->whereId($id)
            ->update($attributes);
    }

    public function findByUserIdAndArticleIn($userId, $articleIds, $version = 'new')
    {
        if($version == 'new') {
            return $this->model
                ->where('user_id', $userId)
                ->whereIn('new_article_id', $articleIds)
                ->get();
        }

        return $this->model
            ->where('user_id', $userId)
            ->whereIn('article_id', $articleIds)
            ->get();
    }
}