<?php

namespace Cactus\Article\Validators;

class ArticleFeedbackValidator extends BaseValidator
{
    public const ARTICLE_CREATE_FEEDBACK = "create_feedback";

    public function __construct()
    {
        $this->rules = [
            self::ARTICLE_CREATE_FEEDBACK => [
                'user_id' => 'required',
                'article_id' => 'required',
                'liked' => 'required|boolean',
                'reason' => 'nullable|string'
            ],
        ];

        $this->messages = [
            self::ARTICLE_CREATE_FEEDBACK => [],
        ];
    }
}