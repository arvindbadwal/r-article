<?php

namespace Cactus\Article\Observers;

use Cactus\Article\Events\ArticleFeedbackSaved;
use Cactus\Article\Models\ArticleFeedback;
use Cactus\Article\Repositories\UserArticleActionInterface;

class ArticleFeedbackObserver
{
    public function created(ArticleFeedback $articleFeedback)
    {
        $version = $articleFeedback->user->version;

        app(UserArticleActionInterface::class)->updateOrCreateAction([
            'user_id' => $articleFeedback->user_id,
            'article_id' => ($version == 'new' ? $articleFeedback->new_article_id : $articleFeedback->article_id),
            'action_performed' => $articleFeedback->liked ? 'liked' : 'dislike'
        ], $version);

        event(new ArticleFeedbackSaved($articleFeedback));
    }

    public function updated(ArticleFeedback $articleFeedback)
    {
        $version = $articleFeedback->user->version;

        app(UserArticleActionInterface::class)->updateOrCreateAction([
            'user_id' => $articleFeedback->user_id,
            'article_id' => ($version == 'new' ? $articleFeedback->new_article_id : $articleFeedback->article_id),
            'action_performed' => $articleFeedback->liked ? 'liked' : 'dislike'
        ], $version);

        event(new ArticleFeedbackSaved($articleFeedback));
    }
}