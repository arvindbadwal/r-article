<?php

namespace Cactus\Article\Repositories;

interface ArticleFeedbackInterface extends BaseRepositoryInterface
{
    public function updateOrCreateFeedback($params, $version);
}