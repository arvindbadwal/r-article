<?php

namespace Cactus\Article\Repositories;

interface UserReadHistoryInterface extends BaseRepositoryInterface
{
    public function updateOrCreateRead($params, $version);
    public function updateArticleMetaById($id, $articleMeta);
}