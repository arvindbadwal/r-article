<?php

namespace Cactus\Article\Facades;

use Illuminate\Support\Facades\Facade;

class ArticleFeedbackFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'article-feedback';
    }
}