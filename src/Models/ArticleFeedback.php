<?php

namespace Cactus\Article\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleFeedback extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'article_id',
        'new_article_id',
        'liked',
        'reason'
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('article.models.user'));
    }
}