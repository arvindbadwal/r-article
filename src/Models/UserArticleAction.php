<?php

namespace Cactus\Article\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserArticleAction extends Model
{
    protected $table = 'user_articles_actions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'article_id',
        'new_article_id',
        'action_performed',
        'article_meta'
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('article.models.user'));
    }
}