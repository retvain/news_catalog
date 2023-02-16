<?php

declare(strict_types=1);

namespace App\Components\NewsRubrics\Models;

use App\Common\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
