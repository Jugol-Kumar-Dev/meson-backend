<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static count()
 * @method static sum(string $string)
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'method',
        'trx',
        'amount',
        'status',
    ];

    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
