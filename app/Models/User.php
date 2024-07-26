<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @method static findOrFail($id)
 * @method static create(array $array)
 * @method static where(string $string, mixed $email)
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'photo',
        'full_phone',
        'dialCode',
        'iso2',
        'countryName',
        'password',
        'about',
        'dob',
        'role',
        'gender',
        'married_status',
        'address',
        'city_id',
        'state_id',
        'country_id',
        'fb_url',
        'twitter_url',
        'youtube_url',
        'linkedin_url',
        'zoom_email',
        'is_active',
        'jwt_token',
        'certificate',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
//        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'datetime:d/m/Y',
    ];

    /**
     * The attributes that should be bcrypted.
     *
     * @var array<string, string>
     */

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => bcrypt($value),
        );
    }

    protected function photo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::url($value) : '/image/image.png',

        );
    }

    protected function certificate(): Attribute{
        return Attribute::make(
            get: fn ($value) => $value ? Storage::url($value) : '/image/image.png',
        );
    }

    public function subscriptions()
    {
        return $this->hasMany('App\Models\Subscription');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction');
    }

    public function witchLists(){
        return $this->hasMany(Wishlist::class, 'user_id');
    }

    public function courses(){
        return $this->hasMany(Course::class, 'user_id');
    }

    public function orders(){
        return $this->hasMany(Order::class, 'user_id');
    }

    public function mocktests()
    {
        return $this->belongsToMany('App\Models\Mocktest')->withTimestamps();
    }
    public function coupons(){
        return $this->belongsToMany(Coupon::class, 'coupon_user')->withPivot(['using', 'using_at'])->withTimestamps();
    }

    public function comments(){
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function chats(){
        return $this->hasMany(Chat::class, 'form_id');
    }

    public function givenMocktestResults()
    {
        return $this->hasMany(MocktestUser::class, 'user_id');
    }
    public function latestGivenMocktestResult()
    {
//        return $this->hasOne(MocktestUser::class)->latestOfMany();
        return $this->hasOne(MocktestUser::class)->oldestOfMany();
    }


    public function givenMocktestAnswers(): BelongsToMany
    {
        return $this->belongsToMany(GivenMocktestAnswer::class, 'given_mocktest_answers', 'users_id')->withTimestamps();
    }


}
