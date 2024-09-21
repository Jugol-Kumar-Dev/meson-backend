<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'name',
        'photo',
        'slug',
    ];

    protected $appends = ['photo_url'];
    /**
     * Get the options for generating the slug.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? Storage::url($this->photo) : NULL;
    }
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    // protected static function boot() {
    //     parent::boot();

    //     static::creating(function ($category) {
    //         if ($category->slug == '') {
    //             $category->slug = preg_replace('/\s+/u', '-', trim($category->name));
    //         }
    //     });
    //     static::updating(function ($category) {
    //         if ($category->slug == '') {
    //             $category->slug = preg_replace('/\s+/u', '-', trim($category->name));
    //         }
    //     });
    // }


    public function sub_categories()
    {
        return $this->hasMany('App\Models\SubCategory');
    }

    public function child_categories()
    {
        return $this->hasMany('App\Models\ChildCategory');
    }

    public function questions()
    {
        return $this->hasMany('App\Models\Question');
    }

    public function courses(){
        return $this->hasMany('App\Models\Course');
    }

    public function blogs(){
        return $this->hasMany(Blog::class, 'category_id');
    }

}
