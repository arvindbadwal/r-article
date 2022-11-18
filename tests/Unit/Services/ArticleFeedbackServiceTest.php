<?php

namespace Cactus\Article\Tests\Unit\Services;

use Cactus\Article\ArticleFeedbackService;
use Cactus\Article\ArticleServiceProvider;
use Cactus\Article\Repositories\ArticleFeedbackInterface;
use Cactus\Article\Repositories\Eloquent\ArticleFeedbackRepository;
use Illuminate\Validation\ValidationException;
use Orchestra\Testbench\TestCase;

class ArticleFeedbackServiceTest extends TestCase
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

        $articleFeedbackService = resolve(ArticleFeedbackService::class);

        $result = $articleFeedbackService->saveFeedback(
            [
                'user_id' => 2,
                'article_id' => '11f96d5d7ada37bb9419de81d943ad7f',
                'liked' => true,
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

        $articleFeedbackService = resolve(ArticleFeedbackService::class);

        $articleFeedbackService->saveFeedback(
            [
                'user_id' => 2,
                'article_id' => '11f96d5d7ada37bb9419de81d943ad7f',
                'liked' => null,
                'reason' => null
            ]
        );

        $this->expectException(ValidationException::class);
    }
}