<?php

namespace Cactus\Article;

use Cactus\Article\Models\ArticleFeedback;
use Cactus\Article\Models\UserArticleAction;
use Cactus\Article\Models\UserReadHistory;
use Cactus\Article\Observers\ArticleFeedbackObserver;
use Cactus\Article\Observers\UserArticleActionObserver;
use Cactus\Article\Observers\UserReadHistoryObserver;
use Cactus\Article\Repositories\ArticleFeedbackInterface;
use Cactus\Article\Repositories\Eloquent\ArticleFeedbackRepository;
use Cactus\Article\Repositories\Eloquent\UserArticleActionRepository;
use Cactus\Article\Repositories\Eloquent\UserReadHistoryRepository;
use Cactus\Article\Repositories\UserArticleActionInterface;
use Cactus\Article\Repositories\UserReadHistoryInterface;
use Cactus\Article\Validators\ArticleFeedbackValidator;
use Cactus\Article\Validators\ArticleHistoryValidator;
use Illuminate\Support\ServiceProvider;
use Cactus\Article\Validators\ArticleValidator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class ArticleServiceProvider extends ServiceProvider
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
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        $this->bootObservers();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('article.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../database/migrations/create_article_feedback.php.stub' => $this->getMigrationFileName('create_article_feedback.php'),
                __DIR__.'/../database/migrations/create_user_articles_actions.php.stub' => $this->getMigrationFileName('create_user_articles_actions.php'),
                __DIR__.'/../database/migrations/create_user_read_histories.php.stub' => $this->getMigrationFileName('create_user_read_histories.php'),
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
            return new ArticleService(new ArticleHistoryValidator());
        });

        $this->app->singleton('article-feedback', function () {
            return new ArticleService(new ArticleFeedbackValidator());
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

    private function bootObservers()
    {
        UserReadHistory::observe(UserReadHistoryObserver::class);
        ArticleFeedback::observe(ArticleFeedbackObserver::class);
        UserArticleAction::observe(UserArticleActionObserver::class);
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
