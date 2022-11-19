<?php

namespace Cactus\Article;

use Cactus\Article\Events\UserArticleActionSaved;
use Cactus\Article\Repositories\ArticleFeedbackInterface;
use Cactus\Article\Validators\ArticleFeedbackValidator;
use DB;
use Illuminate\Validation\ValidationException;

class ArticleFeedbackService
{
    private $validator;

    private $articleFeedbackRepository;

    /**
     * @var string
     */
    private $version = 'new';

    public function __construct(ArticleFeedbackValidator $validator)
    {
        $this->validator = $validator;
        $this->articleFeedbackRepository = app(ArticleFeedbackInterface::class);
    }

    /**
     * @param $version
     * @return $this
     */
    public function setVersion($version): ArticleFeedbackService
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @param array $params
     * @return mixed
     * @throws ValidationException
     */
    public function saveArticleFeedback(array $params)
    {
        $validated = $this->validator->validate($params, ArticleFeedbackValidator::ARTICLE_CREATE_FEEDBACK);

        return $this->articleFeedbackRepository->updateOrCreateFeedback($validated, $this->version);
    }

    /**
     * @param int $userId
     * @param array $articleIds
     * @return array
     */
    public function hasArticleFeedback(int $userId, array $articleIds)
    {
        $userArticleFeedbacks = $this->articleFeedbackRepository->findByUserIdAndArticleIn($userId, $articleIds, $this->version);

        if ($userArticleFeedbacks->isEmpty()) {
            return [];
        }

        if($this->version == 'new') {
            return $userArticleFeedbacks->pluck('new_article_id')->toArray();
        }

        return $userArticleFeedbacks->pluck('article_id')->toArray();
    }
}