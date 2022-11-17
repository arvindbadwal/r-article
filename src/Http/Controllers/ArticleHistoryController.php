<?php

namespace Cactus\Article\Http\Controllers;

use Illuminate\Routing\Controller;
use Cactus\Article\ArticleHistoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ArticleHistoryController extends Controller
{
    private $articleHistoryService;

    /**
     * @param ArticleHistoryService $articleHistoryService
     */
    public function __construct(ArticleHistoryService $articleHistoryService)
    {
        $this->articleHistoryService = $articleHistoryService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function getArticleHistory(Request $request)
    {
        $user = $request->user();

        $articles = $this->articleHistoryService->setVersion($user->version)->getArticlesReadByUser($user->id, $request->all());
        $totalHits = $this->articleHistoryService->setVersion($user->version)->getReadCountForUser($user->id);

        return response()->json([
            'totalHits' => $totalHits,
            'data' => $articles
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param $articleId
     * @return JsonResponse
     * @throws ValidationException
     */
    public function saveArticleRead(Request $request, $articleId)
    {
        $user = $request->user();
        $params = [
            'user_id' => $user->id,
            'article_id' => $articleId,
            'read_via' => $request->get('read_via'),
        ];

        if($this->articleHistoryService->setVersion($user->version)->markArticleRead($params)) {
            return response()->json([
                'message' => 'Article marked as read'
            ], Response::HTTP_CREATED);
        }

        return response()->json([
            'message' => 'Could not mark article as read. Please try again later.'
        ], Reponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}