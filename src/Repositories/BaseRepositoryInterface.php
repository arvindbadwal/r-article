<?php

namespace Cactus\Article\Repositories;

interface BaseRepositoryInterface
{
    public function findByUserIdAndArticleIn($userId, $articleIds, $version);
    public function updateById($id, $attributes);
}