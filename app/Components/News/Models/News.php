<?php

declare(strict_types=1);

namespace App\Components\News\Models;

use App\Components\NewsRubrics\Models\NewsRubric;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'news';

    /**
     * Timestamps on
     *
     * @var bool
     */
    public $timestamps = true;

    protected $fillable = [
        'news_header',
        'news_announcement',
        'news_body',
    ];

    /**
     * @return BelongsToMany
     */
    public function rubrics(): BelongsToMany
    {
        return $this->belongsToMany(NewsRubric::class, 'consolidated_rubric_news', 'news_id', 'news_rubric_id')
            ->whereNull('deleted_at');
    }

    /**
     * @return HasMany
     */
    public function consolidatedRubricNews(): HasMany
    {
        return $this->hasMany(ConsolidatedRubricNews::class, 'news_id', 'id');
    }
}
