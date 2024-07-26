<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail($id)
 */
class Comment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function blog(){
        return $this->belongsTo(Blog::class, 'blog_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replayComments(){
        return $this->hasMany(Comment::class, 'comment_id');
    }

}
