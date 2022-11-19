<?php

namespace Cactus\Article;

use Cactus\Article\Events\UserArticleActionSaved;
use Cactus\Article\Repositories\ArticleFeedbackInterface;
use Cactus\Article\Repositories\UserArticleActionInterface;
use Cactus\Article\Validators\ArticleFeedbackValidator;
use DB;
use Illuminate\Validation\ValidationException;

class ArticleFeedbackService
{
    private $validator;

    private $articleFeedbackRepository;

    private $userArticleAction;

    /**
     * @var string
     */
    private $version = 'new';

    public function __construct(ArticleFeedbackValidator $validator)
    {
        $this->validator = $validator;
        $this->articleFeedbackRepository = app(ArticleFeedbackInterface::class);
        $this->userArticleAction = app(UserArticleActionInterface::class);
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

        DB::transaction(function () use($validated) {
            $this->articleFeedbackRepository->updateOrCreateFeedback($validated, $this->version);
            
            $validated['action_performed'] = 'liked';
            $articleAction = $this->userArticleAction->updateOrCreateAction($validated, $this->version);

            event(new UserArticleActionSaved($articleAction));
        });

        return true;
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