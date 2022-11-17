<?php

namespace Cactus\Article\Http\Controllers;

use Illuminate\Routing\Controller;
use Cactus\Article\ArticleFeedbackService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticleFeedbackController extends Controller
{
    private $articleFeedbackService;

    /**
     * @param ArticleFeedbackService $articleFeedbackService
     */
    public function __construct(ArticleFeedbackService $articleFeedbackService)
    {
        $this->articleFeedbackService = $articleFeedbackService;
    }

    /**
     * @param Request $request
     * @param $articleId
     * @param $reaction
     * @return JsonResponse
     */
    public function saveFeedback(Request $request, $articleId, $reaction)
    {
        $user = $request->user();

        $reason = $request->get('reason');
        $params = [
            'user_id' => $user->id,
            'article_id' => $articleId,
            'liked' => ($reaction == 'like'),
            'reason' => (!is_null($reason) && !is_array($reason)) ? json_encode($reason) : $reason,
        ];

        if($this->articleFeedbackService->setVersion($user->version)->saveFeedback($params)) {
            return response()->json([
                'message' => 'Article feedback saved successfully'
            ], Response::HTTP_CREATED);
        }

        return response()->json([
            'message' => 'Could not save article feedback. Please try again later.'
        ], Reponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}