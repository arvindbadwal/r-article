<?php

namespace Cactus\Article\Tests\Unit\Services;

use Cactus\Article\ArticleFeedbackService;
use Cactus\Article\ArticleServiceProvider;
use Cactus\Article\Repositories\ArticleFeedbackInterface;
use Cactus\Article\Repositories\Eloquent\ArticleFeedbackRepository;
use Cactus\Article\Repositories\Eloquent\UserArticleActionRepository;
use Cactus\Article\Repositories\UserArticleActionInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Orchestra\Testbench\TestCase;

class ArticleFeedbackServiceTest111 extends TestCase
{
    use WithFaker, RefreshDatabase;

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
        $articleService = resolve(ArticleFeedbackService::class);

        $result = $articleService->setVersion('new');

        $this->assertInstanceOf(ArticleFeedbackService::class, $result);
    }

    /**
     * @test
     */
    public function it_performs_set_version_old()
    {
        $articleService = resolve(ArticleFeedbackService::class);

        $result = $articleService->setVersion('old');

        $this->assertInstanceOf(ArticleFeedbackService::class, $result);
    }

    /**
     * @test
     */
    public function it_performs_save_feedback_success()
    {
        $articleFeedbackRepo = $this->mock(ArticleFeedbackRepository::class);
        $articleFeedbackRepo->shouldReceive('updateOrCreateFeedback')->andReturn(true);
        $this->app->instance(ArticleFeedbackInterface::class, $articleFeedbackRepo);

        $userReadHistoryRepo = $this->mock(UserArticleActionRepository::class);
        $userReadHistoryRepo->shouldReceive('updateOrCreateAction')->andReturn(true);
        $this->app->instance(UserArticleActionInterface::class, $userReadHistoryRepo);


        $articleFeedbackService = resolve(ArticleFeedbackService::class);

        $result = $articleFeedbackService->saveFeedback(
            [
                'user_id' => $this->faker->numberBetween(1, 9),
                'article_id' =>  $this->faker->md5(),
                'liked' => $this->faker->boolean(),
                'reason' => null
            ]
        );

        $this->assertTrue($result);
    }

    public function it_performs_save_feedback_throws_validation_exception()
    {
        $articleFeedbackRepo = $this->mock(ArticleFeedbackRepository::class);
        $articleFeedbackRepo->shouldReceive('updateOrCreateFeedback')->andReturn(true);
        $this->app->instance(ArticleFeedbackInterface::class, $articleFeedbackRepo);

        $userReadHistoryRepo = $this->mock(UserArticleActionRepository::class);
        $userReadHistoryRepo->shouldReceive('updateOrCreateAction')->andReturn(true);
        $this->app->instance(UserArticleActionInterface::class, $userReadHistoryRepo);


        $articleFeedbackService = resolve(ArticleFeedbackService::class);

        $articleFeedbackService->saveFeedback(
            [
                'user_id' => $this->faker->numberBetween(1, 9),
                'article_id' => null,
                'liked' => $this->faker->boolean(),
                'reason' => null
            ]
        );

        $this->expectException(ValidationException::class);
    }
}