<?php

namespace Cactus\Article\Repositories;

interface UserArticleActionInterface extends BaseRepositoryInterface
{
    public function updateOrCreateAction($params, $version);
}