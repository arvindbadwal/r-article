<?php

namespace Cactus\Article\Tests;

use Cactus\Article\ArticleServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
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

        // (new \CreateArticleSummaryFeedbacks())->up();
        // (new \CreateUserReadHistories())->up();
    }
}