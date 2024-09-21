<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @method static create(array $data)
 */
class LiveClass extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['photo_url'];
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? Storage::url($this->photo) : NULL;
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

}
