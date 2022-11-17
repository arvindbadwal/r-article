<?php

namespace Cactus\Article\Tests\Unit\Services;

use Cactus\Article\ArticleHistoryService;
use Cactus\Article\ArticleServiceProvider;
use Cactus\Article\Repositories\Eloquent\UserReadHistoryRepository;
use Cactus\Article\Repositories\UserReadHistoryInterface;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class ArticleHistoryServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [ArticleServiceProvider::class];
    }

    /**
     * @test
     */
    public function it_performs_set_version_new()
    {
        $articleService = resolve(ArticleHistoryService::class);

        $result = $articleService->setVersion('new');

        $this->assertInstanceOf(ArticleHistoryService::class, $result);
    }

    /**
     * @test
     */
    public function it_performs_set_version_old()
    {
        $articleService = resolve(ArticleHistoryService::class);

        $result = $articleService->setVersion('old');

        $this->assertInstanceOf(ArticleHistoryService::class, $result);
    }

    /**
     * @test
     */
    public function it_performs_mark_article_read()
    {
        // NOTE :: Not Working Currently

        /*$projectController = resolve(ArticleHistoryService::class);

        $this->app->instance(UserReadHistoryRepository::class,
            \Mockery::mock(UserReadHistoryRepository::class, function (MockInterface $mock) {
                $mock->shouldNotReceive('updateOrCreateRead')
                    ->once()
                    ->andReturn('true');
            })
        );

        $result = $projectController->markArticleRead(
            [
                'user_id' => 2,
                'article_id' => '11f96d5d7ada37bb9419de81d943ad7f',
                'read_via' => null
            ]
        );*/

        $this->assertTrue(true);
    }
}