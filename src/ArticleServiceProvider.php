<?php

namespace Cactus\Article;

use Cactus\Article\Models\ArticleFeedback;
use Cactus\Article\Models\UserArticleAction;
use Cactus\Article\Models\UserReadHistory;
use Cactus\Article\Repositories\ArticleFeedbackInterface;
use Cactus\Article\Repositories\Eloquent\ArticleFeedbackRepository;
use Cactus\Article\Repositories\Eloquent\UserArticleActionRepository;
use Cactus\Article\Repositories\Eloquent\UserReadHistoryRepository;
use Cactus\Article\Repositories\UserArticleActionInterface;
use Cactus\Article\Repositories\UserReadHistoryInterface;
use Cactus\Article\Validators\ArticleFeedbackValidator;
use Cactus\Article\Validators\ArticleHistoryValidator;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Cactus\Article\Validators\ArticleValidator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class ArticleServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('article.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../database/migrations/create_table_article_feedback.php.stub' => $this->getMigrationFileName('create_table_article_feedback.php'),
                __DIR__.'/../database/migrations/create_table_user_read_histories.php.stub' => $this->getMigrationFileName('create_table_user_read_histories.php'),
            ], 'migrations');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'article');

        $this->registerServices();
        $this->registerRepositories();
    }

    private function registerServices()
    {
        $this->app->singleton('article', function () {
            return new ArticleService(new ArticleValidator());
        });

        $this->app->singleton('article-history', function () {
            return new ArticleHistoryService(new ArticleHistoryValidator());
        });

        $this->app->singleton('article-feedback', function () {
            return new ArticleFeedbackService(new ArticleFeedbackValidator());
        });
    }

    private function registerRepositories()
    {
        $this->app->bind(UserReadHistoryInterface::class, function() {
            return new UserReadHistoryRepository(new UserReadHistory());
        });
        $this->app->bind(ArticleFeedbackInterface::class, function() {
            return new ArticleFeedbackRepository(new ArticleFeedback());
        });
        $this->app->bind(UserArticleActionInterface::class, function() {
            return new UserArticleActionRepository(new UserArticleAction());
        });
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @return string
     */
    protected function getMigrationFileName($migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path.'*_'.$migrationFileName);
            })
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}
