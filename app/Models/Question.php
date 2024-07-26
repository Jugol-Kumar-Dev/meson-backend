<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail(mixed $id)
 */
class Question extends Model
{
    use HasFactory;


    protected $fillable = [
        'title',
        'category_id',
        'a',
        'b',
        'c',
        'd',
        'e',
        'answer',
        'mark',
        'feedback',
    ];

    public function category()
    {
        return $this->belongsTo(QuestionCategory::class);
    }

    public function sub_category()
    {
        return $this->belongsTo('App\Models\SubCategory');
    }

    public function child_category()
    {
        return $this->belongsTo('App\Models\ChildCategory');
    }
}
