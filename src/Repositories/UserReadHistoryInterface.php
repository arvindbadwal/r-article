<?php

namespace Cactus\Article\Repositories;

interface UserReadHistoryInterface extends BaseRepositoryInterface
{
    public function updateOrCreateRead($params, $version);
}