<?php

namespace Cactus\Article\Validators;

class ArticleHistoryValidator extends BaseValidator
{
    public const ARTICLE_MARK_READ = "mark_read";
    public const GET_HISTORY = "get_history";

    public function __construct()
    {
        $this->rules = [
            self::ARTICLE_MARK_READ => [
                'user_id' => 'required',
                'article_id' => 'required',
                'read_via' => 'nullable|string'
            ],
            self::GET_HISTORY => [
                'count' => 'nullable|integer',
                'offset' => 'nullable|integer',
                'sort_by' => 'nullable|string|in:created_at',
                'order_by' => 'nullable|string|in:asc,desc'
            ],
        ];

        $this->messages = [
            self::ARTICLE_MARK_READ => [],
            self::GET_HISTORY => [],
        ];
    }
}