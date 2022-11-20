<?php

namespace Cactus\Article\Validators;

class ArticleHistoryValidator extends BaseValidator
{
    public const ARTICLE_MARK_READ = "mark_read";

    public function __construct()
    {
        $this->rules = [
            self::ARTICLE_MARK_READ => [
                'user_id' => 'required',
                'article_id' => 'required',
                'read_via' => 'nullable|string'
            ],
        ];

        $this->messages = [
            self::ARTICLE_MARK_READ => [],
        ];
    }
}