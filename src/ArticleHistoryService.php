<?php

namespace Cactus\Article;

use Cactus\Article\Repositories\UserReadHistoryInterface;
use Cactus\Article\Validators\ArticleHistoryValidator;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class ArticleHistoryService
{
    private $validator;

    private $userReadHistoryRepository;

    /**
     * @var string
     */
    private $version = 'new';

    public function __construct(ArticleHistoryValidator $validator)
    {
        $this->validator = $validator;
        $this->userReadHistoryRepository = app(UserReadHistoryInterface::class);
    }

    /**
     * @param $version
     * @return $this
     */
    public function setVersion($version): ArticleHistoryService
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @param array $params
     * @return mixed
     * @throws ValidationException
     */
    public function markArticleRead(array $params)
    {
        $validated = $this->validator->validate($params, ArticleHistoryValidator::ARTICLE_MARK_READ);

        return $this->userReadHistoryRepository->updateOrCreateRead($validated, $this->version);
    }

    /**
     * @param int $userId
     * @param array $articleIds
     * @return array
     */
    public function isArticlesRead(int $userId, array $articleIds)
    {
        $userReadHistories = $this->userReadHistoryRepository->findByUserIdAndArticleIn($userId, $articleIds, $this->version);

        if ($userReadHistories->isEmpty()) {
            return [];
        }

        if($this->version == 'new') {
            return $userReadHistories->pluck('new_article_id')->toArray();
        }

        return $userReadHistories->pluck('article_id')->toArray();
    }

    /**
     * @param int $articleReadId
     * @param array $articleMeta
     * @return mixed
     */
    public function updateArticleMeta(int $articleReadId, array $articleMeta)
    {
        return $this->userReadHistoryRepository->updateById($articleReadId, [
            'article_meta' => $articleMeta,
            'is_active' => true
        ]);
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function getReadCountForUser(int $userId)
    {
        return $this->userReadHistoryRepository->getHistoryCountBasedOnUserVersion($userId, $this->version);
    }

    /**
     * @param int $userId
     * @param array $params
     * @return array
     * @throws ValidationException
     */
    public function getArticlesReadByUser(int $userId, array $params = [])
    {
        /* TODO NOTE:: currently the implementation is different from discovery code, as there is other dependency of models and services */

        $validated = $this->validator->validate($params, ArticleHistoryValidator::GET_HISTORY);

        // get the builder query
        $userReadHistory = $this->userReadHistoryRepository->getUserReadHistoryBuilder($userId, $this->version);

        if(!empty($userReadHistory)) {
            $userHistory = $userReadHistory->orderBy(
                Arr::get($validated, 'sort_by', 'created_at'),
                Arr::get($validated, 'order_by', 'desc')
            )
                ->take(Arr::get($validated, 'count', 20))
                ->offset(Arr::get($validated, 'offset', 0))
                ->get();

            return $userHistory->transform(function ($item) {
                $articleMeta = is_array($item->article_meta) ? $item->article_meta : (array) json_decode($item->article_meta, true);
                $articleMeta[ 'id' ] = $item->new_article_id;
                $item[ 'article' ] = $articleMeta;

                return $item;
            });
        }

        return [];
    }
}