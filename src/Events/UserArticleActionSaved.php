<?php

namespace Cactus\Article\Events;

use Cactus\Article\Models\UserArticleAction;

class UserArticleActionSaved
{
    public $userArticleAction;

    public function __construct(UserArticleAction $userArticleAction)
    {
        $this->userArticleAction = $userArticleAction;
    }
}