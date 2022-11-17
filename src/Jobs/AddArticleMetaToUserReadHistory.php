<?php

namespace Cactus\Article\Jobs;

use App\Services\DysonService\DysonService;
use App\Services\DysonService\Resources\ElasticsearchUnsiloMapperResource;
use Cactus\Article\ArticleHistoryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;

class AddArticleMetaToUserReadHistory implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    private $readHistoryId;
    /**
     * @var string
     */
    private $articleId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $readHistoryId, string $articleId)
    {
        $this->readHistoryId = $readHistoryId;
        $this->articleId = $articleId;
    }

    public function handle(
        ArticleHistoryService $articleHistoryService,
        DysonService             $dysonService
    )
    {
        $rawResponseFromElasticsearch = $dysonService->searchByArticleId($this->articleId);
        $transformedResponse = new ElasticsearchUnsiloMapperResource($rawResponseFromElasticsearch);
        $metaData = collect($transformedResponse)->map(function ($article) {
            return [
                'title' => $article->title,
                'doi' => $article->doi,
                'journal' => $article->journal,
                'publicationDate' => $article->publicationDate,
                'concepts' => $article->concepts,
                'authors' => $article->authors,
                'customFields' => Arr::only($article->customFields, ['links', 'volume', 'publisher', 'primary_source'])
            ];
        })->first();

        $articleHistoryService->updateArticleMeta($this->readHistoryId, $metaData);
    }
}
