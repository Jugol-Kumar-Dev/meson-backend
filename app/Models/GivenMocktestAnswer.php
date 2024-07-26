<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GivenMocktestAnswer extends Model
{
    use HasFactory;


    protected $casts =[
        'created_at' => 'datetime:y-m-d'
    ];

    protected $guarded = ['id'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'qustion_id');
    }

}
