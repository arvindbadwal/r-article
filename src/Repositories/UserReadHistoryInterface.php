<?php

namespace Cactus\Article\Repositories;

interface UserReadHistoryInterface extends BaseRepositoryInterface
{
    public function updateOrCreateRead($params, $version);

    public function getUserReadHistoryBuilder(int $userId, $version);

    public function getHistoryCountBasedOnUserVersion(int $userId, $version);
}