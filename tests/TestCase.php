<?php

namespace Cactus\Article\Tests;

use Cactus\Article\ArticleServiceProvider;
use Cactus\Article\Models\ArticleFeedback;
use Cactus\Article\Models\UserReadHistory;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Illuminate\Foundation\Testing\WithFaker;

class TestCase extends OrchestraTestCase
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

    protected function getEnvironmentSetUp($app)
    {
        require_once __DIR__.'/../database/migrations/create_table_article_feedback.php.stub';
        require_once __DIR__.'/../database/migrations/create_table_user_read_histories.php.stub';

        (new \CreateArticleFeedbacks())->up();
        (new \CreateUserReadHistories())->up();
    }

    /**
     * @test
     */
    public function it_performs_create_user_read_history()
    {
        $create = UserReadHistory::create([
            'user_id' => $this->faker->numberBetween(1, 9),
            'new_article_id' => $this->faker->md5(),
            'read_via' => null
        ]);

        $this->assertInstanceOf(UserReadHistory::class, $create);
        $this->assertDatabaseHas(UserReadHistory::class, ['id' => $create->id]);
    }

    /**
     * @test
     */
    public function it_performs_create_article_feedback()
    {
        $create = ArticleFeedback::create(
            [
                'user_id' => $this->faker->numberBetween(1, 9),
                'new_article_id' =>  $this->faker->md5(),
                'liked' => $this->faker->boolean(),
                'reason' => null
            ]
        );

        $this->assertInstanceOf(ArticleFeedback::class, $create);
        $this->assertDatabaseHas(ArticleFeedback::class, ['id' => $create->id]);
    }
}