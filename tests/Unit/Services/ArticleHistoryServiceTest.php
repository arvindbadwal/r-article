<?php

namespace Cactus\Article\Tests\Unit\Services;

use Cactus\Article\ArticleHistoryService;
use Cactus\Article\ArticleServiceProvider;
use Cactus\Article\Repositories\Eloquent\UserReadHistoryRepository;
use Cactus\Article\Repositories\UserReadHistoryInterface;
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
        $userReadHistoryRepo = $this->mock(UserReadHistoryRepository::class);
        $userReadHistoryRepo->shouldReceive('updateOrCreateRead')->andReturn(true);
        $this->app->instance(UserReadHistoryInterface::class, $userReadHistoryRepo);

        $articleHistoryService = resolve(ArticleHistoryService::class);

        $result = $articleHistoryService->markArticleRead(
            [
                'user_id' => 2,
                'article_id' => '11f96d5d7ada37bb9419de81d943ad7f',
                'read_via' => null
            ]
        );

        $this->assertTrue($result);
    }
}