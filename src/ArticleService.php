<?php

namespace Cactus\Article;

use Cactus\Article\Repositories\ArticleFeedbackInterface;
use Cactus\Article\Repositories\UserReadHistoryInterface;
use Cactus\Article\Validators\ArticleValidator;
use Exception;
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
     * @param null $userId
     * @return mixed
     * @throws Exception
     */
    public function getArticleMeta($articleId)
    {
        $content_profile = is_numeric($articleId) ? config('article.unsilo.old_index') : config('article.unsilo.new_index');
        $unsiloService = config('article.services.unsilo') ? app(config('article.services.unsilo')) : null;

        // TODO :: Currently we dont have any package for unsilo, the unsilo service will depend on the application
        // TODO :: The setting for unsilo service should be defined in article config

        if(!$content_profile || !class_exists($unsiloService)) {
            throw new Exception('Configuration of Unsilo is missing from article config.');
        }

        $articleMeta = Arr::first($unsiloService)->getArticleMeta([$articleId], $content_profile);

        if (empty($articleMeta)) {
            throw new Exception("Article meta not found for article Id: {$articleId}");
        }

        return $articleMeta;
    }

    /**
     * @throws Exception
     */
    public function getArticleMetaForUser($articleId, $userId)
    {
            $articleMeta = $this->getArticleMeta($articleId);

            if(is_numeric($articleId)) {
                $condition = ['article_id' => $articleId, 'user_id' => $userId];
            } else {
                $condition = ['new_article_id' => $articleId, 'user_id' => $userId];
            }

            $articleFeedback = app(ArticleFeedbackInterface::class)->findWhere($condition);
            $userReadHistory = app(UserReadHistoryInterface::class)->findWhere($condition);

            $articleMeta['liked'] =  optional($articleFeedback)->liked;
            $articleMeta['reason'] = optional($articleFeedback)->reason;
            $articleMeta['is_read'] = (bool) $userReadHistory;

            return $articleMeta;
    }
}

