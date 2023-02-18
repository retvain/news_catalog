<?php

declare(strict_types=1);

namespace App\Components\NewsRubrics\Models;

use App\Common\Services\Search;
use App\Common\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

/**
 * @method static cursor()
 */
class NewsRubric extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    public const NO_RUBRIC_ID = 1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'news_rubrics';

    /**
     * Timestamps on
     *
     * @var bool
     */
    public $timestamps = true;

    protected $fillable = [
        'id',
        'parent_id',
        'rubric_name'
    ];
    public static function boot()
    {
        /** @var Search $searchClient */
        $searchClient = App::make(Search::class);

        parent::boot();

        self::created(
            function () use ($searchClient) {
                $searchClient->refreshNewsRubricsIndexes();
            }
        );

        self::updated(
            function () use ($searchClient) {
                $searchClient->refreshNewsRubricsIndexes();
            }
        );

        self::deleted(
            function () use ($searchClient) {
                $searchClient->refreshNewsRubricsIndexes();
            }
        );
    }

}
