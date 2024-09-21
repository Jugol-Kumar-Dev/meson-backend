<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @method static create(array $data)
 * @method static findOrFail($id)
 * @method static where(string $string, $slug)
 * @property mixed image
 */
class Blog extends Model
{
    use HasFactory, HasSlug;

    protected $guarded = ['id'];
    protected $appends = ['image_url'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
    protected function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(){
        return $this->hasMany(Comment::class, 'blog_id');
    }


}
