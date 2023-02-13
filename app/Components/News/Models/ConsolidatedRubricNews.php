<?php

declare(strict_types=1);

namespace App\Components\News\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsolidatedRubricNews extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'consolidated_rubric_news';

    /**
     * Timestamps on
     *
     * @var bool
     */
    public $timestamps = true;

    protected $fillable = [
        'news_id',
        'news_rubric_id'
    ];
}
