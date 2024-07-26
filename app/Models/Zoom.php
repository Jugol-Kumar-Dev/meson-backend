<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'meeting_id',
        'owner_id',
        'start_time',
        'zoom_url',
        'join_url',
        'user_id',
        'meeting_title',
        'course_id',
        'link_by',
        'type',
        'agenda',
        'photo'
    ];
}
