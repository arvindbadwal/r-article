<?php

namespace Cactus\Article\Tests\Unit\Validators;

use Cactus\Article\ArticleServiceProvider;
use Cactus\Article\Validators\ArticleFeedbackValidator;
use Illuminate\Validation\ValidationException;
use Orchestra\Testbench\TestCase;

class ArticleFeedbackValidatorTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [ArticleServiceProvider::class];
    }

    public function it_performs_throws_validation_exception()
    {
        $validator = new ArticleFeedbackValidator();

        $this->expectException(ValidationException::class);

        $validator->validate([
            'user_id' => 2,
            'article_id' => '11f96d5d7ada37bb9419de81d943ad7f',
            'liked' => null,
        ], ArticleFeedbackValidator::ARTICLE_CREATE_FEEDBACK);
    }

    /**
     * @test
     */
    public function it_performs_throws_validation_exception_for_datatype_like()
    {
        $validator = new ArticleFeedbackValidator();

        $this->expectException(ValidationException::class);

        $validator->validate([
            'user_id' => 2,
            'article_id' => '11f96d5d7ada37bb9419de81d943ad7f',
            'liked' => 'like'
        ], ArticleFeedbackValidator::ARTICLE_CREATE_FEEDBACK);
    }

    /**
     * @test
     */
    public function it_performs_throws_validation_exception_for_required_article_id()
    {
        $validator = new ArticleFeedbackValidator();

        $this->expectException(ValidationException::class);

        $validator->validate([
            'user_id' => 2,
            'article_id' => null,
            'liked' => true
        ], ArticleFeedbackValidator::ARTICLE_CREATE_FEEDBACK);
    }

    /**
     * @test
     */
    public function it_performs_does_not_throws_validation_exception()
    {
        $validator = new ArticleFeedbackValidator();

        $result = $validator->validate([
            'user_id' => 2,
            'article_id' => '11f96d5d7ada37bb9419de81d943ad7f',
            'liked' => true
        ], ArticleFeedbackValidator::ARTICLE_CREATE_FEEDBACK);

        $this->assertIsArray($result);
    }
}
