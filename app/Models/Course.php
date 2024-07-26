<?php

namespace App\Models;

use Carbon\Carbon;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static paginate(int $int)
 * @method static findOrFail($id)
 * @method static count()
 */
class Course extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'content',
        'cover',
        'video',
        'files',
        'price',
        'user_id',
        'category_id',
        'active_on',
        'status',
    ];

    protected $casts = [
        'active_on' => 'datetime:d/m/Y',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function subscriptions()
    {
        return $this->hasMany('App\Models\Subscription');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function zoomes(){
        return $this->hasMany(Zoom::class, 'course_id');
    }

    public function lessons(){
        return $this->hasMany('App\Models\Lesson');
    }

    protected function cover(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::url($value) : '/image/image.png',
        );
    }

    protected function files(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::url($value) : null,
        );
    }

    public function mocktests(){
        return $this->belongsToMany(Mocktest::class,'course_mocktest')->withPivot('status')->withTimestamps();
    }
    public function chapters(){
        return $this->hasMany(Chapter::class);
    }

    public function chapterVideos()
    {
        return $this->hasManyThrough( Lesson::class, Chapter::class);
    }

}
