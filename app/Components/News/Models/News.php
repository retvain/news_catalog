<?php

declare(strict_types=1);

namespace App\Components\News\Models;

use App\Common\Services\Search;
use App\Common\Traits\Searchable;
use App\Components\NewsRubrics\Models\NewsRubric;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

/**
 * @method static cursor()
 */
class News extends Model
{
    use HasFactory, SoftDeletes, Searchable;

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

    public static function boot()
    {
        /** @var Search $searchClient */
        $searchClient = App::make(Search::class);

        parent::boot();

        self::created(
            function () use ($searchClient) {
                $searchClient->refreshNewsIndexes();
            }
        );

        self::updated(
            function () use ($searchClient) {
                $searchClient->refreshNewsIndexes();
            }
        );

        self::deleted(
            function () use ($searchClient) {
                $searchClient->refreshNewsIndexes();
            }
        );
    }

    /**
     * @return HasMany
     */
    public function consolidatedRubricNews(): HasMany
    {
        return $this->hasMany(ConsolidatedRubricNews::class, 'news_id', 'id');
    }

    // todo в модели поставить событие на переиндексацию после сохранения
    // todo клиента эластик - синглтон
    // todo добавить csv для добавления новостей
}
