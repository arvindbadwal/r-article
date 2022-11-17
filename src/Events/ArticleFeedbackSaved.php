<?php

namespace Cactus\Article\Events;

use Cactus\Article\Models\ArticleFeedback;

class ArticleFeedbackSaved
{
    public $articleFeedback;

    public function __construct(ArticleFeedback $articleFeedback)
    {
        $this->articleFeedback = $articleFeedback;
    }
}