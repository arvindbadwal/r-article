<?php

namespace Cactus\Article\Observers;


use Cactus\Article\Events\UserArticleActionSaved;
use Cactus\Article\Models\UserArticleAction;

class UserArticleActionObserver
{
    public function created(UserArticleAction $userArticleAction)
    {
        event(new UserArticleActionSaved($userArticleAction));
    }

    public function updated(UserArticleAction $userArticleAction)
    {
        event(new UserArticleActionSaved($userArticleAction));
    }
}