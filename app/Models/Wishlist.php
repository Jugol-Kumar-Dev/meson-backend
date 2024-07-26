<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
    ];

    public function course(){
        return $this->belongsTo("App\Models\Course");
    }
}