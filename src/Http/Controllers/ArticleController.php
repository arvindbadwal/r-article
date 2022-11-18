<?php

namespace Cactus\Article\Http\Controllers;

use Cactus\Article\ArticleService;
use Illuminate\Http\Request;

class ArticleController
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * @throws \Exception
     */
    public function articleMeta(Request $request, $articleId)
    {
        $user = $request->user();
        if ($user) {
            $articleMeta = $this->articleService->setVersion($user->version)->getArticleMeta($articleId, $user->id);
        } else {
            $articleMeta = $this->articleService->getArticleMeta($articleId);
        }

        return $articleMeta;
    }
}