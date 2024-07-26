<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static findOrFail($id)
 */
class MoktestLink extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function courses(){
        return $this->belongsToMany(Course::class, 'course_mocktest');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'mocktest_link_user');
    }



}
