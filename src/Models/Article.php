<?php

namespace Cactus\Article\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'doi',
        'title',
        'journal',
        'publicationDate',
        'abstractText',
        'customFields',
        'isIndexed',
        'concepts',
        'authors',
        'getFtr',
        'score',
        'relevanceScore',
        'general_tags',
        'fingerprint_tags',
        'facets'
    ];

    protected $appends = ['slug'];

    public function getSlugAttribute()
    {
        return prepareSlugFromTitle($this->title);
    }
}