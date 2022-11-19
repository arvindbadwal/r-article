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

    public function findWhere($cols = [])
    {
        return $this->model
            ->where($cols)
            ->first();
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