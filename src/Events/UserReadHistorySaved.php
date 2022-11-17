<?php

namespace Cactus\Article\Events;

use Cactus\Article\Models\UserReadHistory;

class UserReadHistorySaved
{
    public $userReadHistory;

    public function __construct(UserReadHistory $userReadHistory)
    {
        $this->userReadHistory = $userReadHistory;
    }
}