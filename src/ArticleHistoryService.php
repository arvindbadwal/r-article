<?php

namespace Cactus\Article;

use Cactus\Article\Events\UserReadHistorySaved;
use Cactus\Article\Repositories\UserReadHistoryInterface;
use Cactus\Article\Validators\ArticleHistoryValidator;
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
    public function saveArticleRead(array $params)
    {
        $validated = $this->validator->validate($params, ArticleHistoryValidator::ARTICLE_MARK_READ);

        $articleRead = $this->userReadHistoryRepository->updateOrCreateRead($validated, $this->version);

        event(new UserReadHistorySaved($articleRead));

        return $articleRead;
    }

    /**
     * @param int $userId
     * @param array $articleIds
     * @return array
     */
    public function isArticleRead(int $userId, array $articleIds)
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
}