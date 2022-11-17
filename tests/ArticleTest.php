<?php

namespace Cactus\Article\Tests;

use Cactus\Article\Article;
use Cactus\Article\ArticleServiceProvider;
use Illuminate\Validation\ValidationException;
use Orchestra\Testbench\TestCase;

class ArticleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [ArticleServiceProvider::class];
    }

    /**
     * @throws ValidationException
     */
    public function test_make_read_success()
    {
        $articleService = $this->app[Article::class];

        $version = 'new';
        $params = [
            'user_id' => 2,
            'article_id' => '11f96d5d7ada37bb9419de81d943ad7f',
            'read_via' => 'search',
        ];

        if($articleService->setVersion($version)->markRead($params)) {
          return $this->assertTrue(true);
        }

        return $this->assertTrue(false);
    }
}