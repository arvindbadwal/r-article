<?php

namespace Cactus\Article\Repositories\Eloquent;

use Cactus\Article\Repositories\UserReadHistoryInterface;
use Illuminate\Support\Facades\DB;

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

    public function getUserReadHistoryBuilder(int $userId, $version = 'new')
    {
        $query = $this->model::where('user_id', $userId);
        if ($version == 'new') {
            return $query->whereNotNull('new_article_id')
                ->where('new_article_id', '!=', 'missing_in_dynamodb')
                ->where('is_active', true)
                ->select(DB::raw('new_article_id, to_jsonb(article_meta) AS article_meta, created_at'));
        } else {
            return $query->whereNotNull('article_id')
                ->select(DB::raw('article_id AS new_article_id, to_jsonb(article_meta) AS article_meta, created_at'));
        }
    }

    public function getHistoryCountBasedOnUserVersion(int $userId, $version = 'old')
    {
        if ($version == 'new') {
            return $this->model::where('user_id', $userId)
                ->whereNotNull('new_article_id')
                ->where('new_article_id', '!=', 'missing_in_dynamodb')
                ->where('is_active', true)
                ->count();
        }

        return $this->model::where('user_id', $userId)
            ->whereNotNull('article_id')
            ->count();
    }
}