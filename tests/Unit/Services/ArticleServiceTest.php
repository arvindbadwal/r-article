<?php

namespace Cactus\Article\Tests\Unit\Services;

use Cactus\Article\ArticleService;
use Cactus\Article\ArticleServiceProvider;
use Cactus\Article\Validators\ArticleValidator;
use Orchestra\Testbench\TestCase;

class ArticleServiceTest extends TestCase
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
        $articleService = resolve(ArticleService::class);

        $result = $articleService->setVersion('new');

        $this->assertInstanceOf(ArticleService::class, $result);
    }

    /**
     * @test
     */
    public function it_performs_set_version_old()
    {
        $articleService = resolve(ArticleService::class);

        $result = $articleService->setVersion('old');

        $this->assertInstanceOf(ArticleService::class, $result);
    }
}