<?php

namespace Cactus\Article;

use Cactus\Article\Validators\ArticleValidator;

/**
 *
 */
class ArticleService
{
    private $validator;

    /**
     * @var string
     */
    private $version = 'new';

    public function __construct(ArticleValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $version
     * @return $this
     */
    public function setVersion($version): ArticleService
    {
        $this->version = $version;

        return $this;
    }
}
