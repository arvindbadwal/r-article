<?php

namespace Cactus\Article;

use Cactus\Article\Repositories\UserReadHistoryInterface;
use Cactus\Article\Validators\ArticleHistoryValidator;
use Illuminate\Validation\ValidationException;
use Exception;

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

        return $this->userReadHistoryRepository->updateOrCreateRead($validated, $this->version);
    }

    /**
     * @param int $userId
     * @param array $articleIds
     * @return array
     */
    public function isArticleRead(int $userId, array $articleIds): array
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
     * @param $readId
     * @param $articleMeta
     * @return mixed
     * @throws Exception
     */
    public function updateArticleMeta($readId, $articleMeta)
    {
        try {
            return $this->userReadHistoryRepository->updateArticleMetaById($readId, $articleMeta);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}