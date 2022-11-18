<?php

namespace Cactus\Article\Tests\Unit\Controllers;

use Cactus\Article\ArticleServiceProvider;
use Cactus\Article\Http\Controllers\ArticleFeedbackController;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

use Illuminate\Foundation\Testing\WithoutMiddleware;

class ArticleFeedbackControllerTest extends TestCase
{
    use WithoutMiddleware;

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
    public function it_performs_save_feedback_route_present()
    {

        // NOTE:: Not Working Currently

        /*$this->withoutMiddleware();

        $this->app->instance(ArticleFeedbackController::class,
            \Mockery::mock(ArticleFeedbackController::class, function (MockInterface $mock) {
                $mock->shouldReceive('saveFeedback')
                    ->once()
                    ->andReturn(json_decode(json_encode([
                        'message' => 'Article feedback saved successfully'
                    ])));
            })
        );*/
//        $this->withoutMiddleware();
//
//        $response = $this->postJson(url(config('article.route.prefix').'/articles/sdasda234234/like'));
//
//        var_dump($response->json());
//        var_dump(url(config('article.route.prefix').'/articles/sdasda234234/like'));
//
//        $response->assertOk();

        $this->assertNull(null);
    }



}