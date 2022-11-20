<?php

namespace Cactus\Article\Tests\Unit\Services;

use Cactus\Article\ArticleHistoryService;
use Cactus\Article\ArticleServiceProvider;
use Cactus\Article\Models\UserReadHistory;
use Cactus\Article\Repositories\Eloquent\UserReadHistoryRepository;
use Cactus\Article\Repositories\UserReadHistoryInterface;
use Illuminate\Validation\ValidationException;
// use Orchestra\Testbench\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Cactus\Article\Tests\TestCase;

class ArticleHistoryServiceTest extends TestCase
{
    use WithFaker;

    protected $articleHistoryService;

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
        $this->app->instance(UserReadHistoryInterface::class, UserReadHistoryRepository::class);
        $articleService = resolve(ArticleHistoryService::class);

        $result = $articleService->setVersion('new');

        $this->assertInstanceOf(ArticleHistoryService::class, $result);
    }

    /**
     * @test
     */
    public function it_performs_set_version_old()
    {
        $this->app->instance(UserReadHistoryInterface::class, UserReadHistoryRepository::class);
        $articleService = resolve(ArticleHistoryService::class);

        $result = $articleService->setVersion('old');

        $this->assertInstanceOf(ArticleHistoryService::class, $result);
    }

    /**
     * @test
     * @throws ValidationException
     */
    public function it_performs_mark_article_read()
    {
        $userReadHistoryRepo = $this->mock(UserReadHistoryRepository::class);
        $userReadHistoryRepo->shouldReceive('updateOrCreateRead')->andReturn(new UserReadHistory);
        $this->app->instance(UserReadHistoryInterface::class, $userReadHistoryRepo);

        $articleHistoryService = resolve(ArticleHistoryService::class);

        $result = $articleHistoryService->saveArticleRead(
            [
                'user_id' => $this->faker->numberBetween(1, 9),
                'article_id' => $this->faker->md5(),
                'read_via' => null
            ]
        );

        $this->assertInstanceOf(UserReadHistory::class, $result);
    }

    /**
     * @test
     * @throws ValidationException
     */
    public function it_performs_mark_article_throws_validation_exception()
    {
        $userReadHistoryRepo = $this->mock(UserReadHistoryRepository::class);
        $userReadHistoryRepo->shouldReceive('updateOrCreateRead')->andReturn(new UserReadHistory);
        $this->app->instance(UserReadHistoryInterface::class, $userReadHistoryRepo);

        $articleHistoryService = resolve(ArticleHistoryService::class);

        $this->expectException(ValidationException::class);

        $articleHistoryService->saveArticleRead(
            [
                'user_id' => $this->faker->numberBetween(1, 9),
                'article_id' => null,
                'read_via' => null
            ]
        );
    }
}