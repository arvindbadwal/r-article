<?php

namespace Cactus\Article\Tests\Unit\Services;

use Cactus\Article\ArticleFeedbackService;
use Cactus\Article\ArticleServiceProvider;
use Cactus\Article\Models\ArticleFeedback;
use Cactus\Article\Repositories\ArticleFeedbackInterface;
use Cactus\Article\Repositories\Eloquent\ArticleFeedbackRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
//use Orchestra\Testbench\TestCase;
use Cactus\Article\Tests\TestCase;

class ArticleFeedbackServiceTest extends TestCase
{
    use WithFaker;


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
        $this->app->instance(ArticleFeedbackInterface::class, ArticleFeedbackRepository::class);
        $articleService = resolve(ArticleFeedbackService::class);

        $result = $articleService->setVersion('new');

        $this->assertInstanceOf(ArticleFeedbackService::class, $result);
    }

    /**
     * @test
     */
    public function it_performs_set_version_old()
    {
        $this->app->instance(ArticleFeedbackInterface::class, ArticleFeedbackRepository::class);
        $articleService = resolve(ArticleFeedbackService::class);

        $result = $articleService->setVersion('old');

        $this->assertInstanceOf(ArticleFeedbackService::class, $result);
    }

    /**
     * @test
     * @throws ValidationException
     */
    public function it_performs_save_feedback_success()
    {
        $articleFeedbackRepo = $this->mock(ArticleFeedbackRepository::class);
        $articleFeedbackRepo->shouldReceive('updateOrCreateFeedback')->andReturn(new ArticleFeedback());
        $this->app->instance(ArticleFeedbackInterface::class, $articleFeedbackRepo);

        $articleFeedbackService = resolve(ArticleFeedbackService::class);

        $result = $articleFeedbackService->saveArticleFeedback(
            [
                'user_id' => $this->faker->numberBetween(1, 9),
                'article_id' =>  $this->faker->md5(),
                'liked' => $this->faker->boolean(),
                'reason' => null
            ]
        );

        $this->assertInstanceOf(ArticleFeedback::class, $result);
    }

    /**
     * @throws ValidationException
     */
    public function it_performs_save_feedback_throws_validation_exception()
    {
        $articleFeedbackRepo = $this->mock(ArticleFeedbackRepository::class);
        $articleFeedbackRepo->shouldReceive('updateOrCreateFeedback')->andReturn(true);
        $this->app->instance(ArticleFeedbackInterface::class, $articleFeedbackRepo);

        $articleFeedbackService = resolve(ArticleFeedbackService::class);

        $articleFeedbackService->saveArticleFeedback(
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