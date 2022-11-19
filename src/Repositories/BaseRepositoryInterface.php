<?php

namespace Cactus\Article\Repositories;

interface BaseRepositoryInterface
{
    public function findWhere($cols = []);
    public function findByUserIdAndArticleIn($userId, $articleIds, $version);
}