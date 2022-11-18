<?php

namespace Cactus\Article;

use Cactus\Article\Models\Article;
use Cactus\Article\Repositories\ArticleFeedbackInterface;
use Cactus\Article\Repositories\UserReadHistoryInterface;
use Cactus\Article\Validators\ArticleValidator;
use Illuminate\Support\Arr;

/**
 *
 */
class ArticleService
{
    private $validator;

    /**
     * @var string
     */
    private $version = 'new';

    public function __construct(ArticleValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $version
     * @return $this
     */
    public function setVersion($version): ArticleService
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @param $articleId
     * @param $userId
     * @return Article[]
     * @throws \Exception
     */
    public function getArticleMeta($articleId, $userId)
    {
        $content_profile = is_numeric($articleId) ? config('unsilo.old_index') : config('unsilo.new_index');
        $articleMeta = $this->getMetadataFromUnsilo($articleId, $content_profile);

        if (empty($articleMeta)) {
            throw new \Exception("Article meta not found for article Id: {$articleId}");
        }

        $article = new Article($articleMeta);

        if($userId) {
            if(is_numeric($articleId)) {
                $condition = ['article_id' => $articleId, 'user_id' => $userId];
            } else {
                $condition = ['new_article_id' => $articleId, 'user_id' => $userId];
            }

            $articleFeedback = app(ArticleFeedbackInterface::class)->findWhere($condition);
            $userReadHistory = app(UserReadHistoryInterface::class)->findWhere($condition);

            $article->liked =  optional($articleFeedback)->liked;
            $article->reason = optional($articleFeedback)->reason;
            $article->is_read = (bool) $userReadHistory;
        }

        return [$article];
    }

    /**
     * @param        $articleId
     * @param string $content_profile
     * @return mixed
     */
    public function getMetadataFromUnsilo($articleId, string $content_profile = 'test-rdiscovery')
    {
        if(config('article.services.unsilo')) {
            return Arr::first(app(config('article.services.unsilo'))->getArticleMeta([$articleId], $content_profile));

        }

        return null;
    }


}
