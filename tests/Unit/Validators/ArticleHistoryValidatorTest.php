<?php

namespace Cactus\Article\Tests\Unit\Validators;

use Cactus\Article\ArticleServiceProvider;
use Cactus\Article\Validators\ArticleHistoryValidator;
use Illuminate\Validation\ValidationException;
use Orchestra\Testbench\TestCase;

class ArticleHistoryValidatorTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [ArticleServiceProvider::class];
    }

    /**
     * @test
     */
    public function it_performs_throws_validation_exception_for_required_article_id()
    {
        $validator = new ArticleHistoryValidator();

        $this->expectException(ValidationException::class);

        $validator->validate([
            'user_id' => 2,
            'article_id' => null,
            'read_via' => null
        ], ArticleHistoryValidator::ARTICLE_MARK_READ);
    }

    /**
     * @test
     */
    public function it_performs_does_not_throws_validation_exception()
    {
        $validator = new ArticleHistoryValidator();

        $result = $validator->validate([
            'user_id' => 2,
            'article_id' => '11f96d5d7ada37bb9419de81d943ad7f',
            'read_via' => null
        ], ArticleHistoryValidator::ARTICLE_MARK_READ);

        $this->assertIsArray($result);
    }

    /**
     * @test
     */
    public function it_performs_does_not_throws_validation_exception_with_read_via()
    {
        $validator = new ArticleHistoryValidator();

        $result = $validator->validate([
            'user_id' => 2,
            'article_id' => '11f96d5d7ada37bb9419de81d943ad7f',
            'read_via' => 'Search'
        ], ArticleHistoryValidator::ARTICLE_MARK_READ);

        $this->assertIsArray($result);
    }
}
