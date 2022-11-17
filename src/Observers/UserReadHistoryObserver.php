<?php

namespace Cactus\Article\Observers;

use Cactus\Article\Events\UserReadHistoryCreated;
use Cactus\Article\Models\UserReadHistory;

class UserReadHistoryObserver
{
    public function created(UserReadHistory $userReadHistory)
    {
        event(new UserReadHistorySaved($userReadHistory));
    }

    public function updated(UserReadHistory $userReadHistory)
    {

    }
}