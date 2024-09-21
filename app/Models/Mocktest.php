<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static findOrFail($id)
 * @method static create(array $data)
 */
class Mocktest extends Model
{
    use HasFactory;

//
//    protected $fillable = [
//        'name',
//        'total_q',
//        'duration',
//        // 'categories',
//        'user_id',
//        'status',
//        'about',
//        'show_student'
//    ];
    protected $guarded = ['id'];

    protected $casts = [
        // 'categories' => 'array',
    ];
//    protected $hidden = [
//        'password'
//    ];

    public function getPass()
    {
        return $this->password;
    }
    protected $appends = ['isPassword'];
    public function getIsPasswordAttribute(): string
    {
        return $this->password != null;
    }


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function sub_categories()
    {
        return $this->belongsToMany('App\Models\SubCategory')->withPivot('question')->withTimestamps();
    }

    public function users() // get users who already attend this mock
    {
        return $this->belongsToMany('App\Models\User', 'mocktest_user');//->withPivot('exam_token');//->withTimestamps();//->withPivot('mark')->withTimestamps();
    }

    public function givenAnswers() // who users already aswers this mock all  answers
    {
        return $this->belongsToMany(User::class, 'mocktest_answer');//->withPivot('')
    }

    public function courses(){
        return $this->belongsToMany(Course::class,'course_mocktest')->withTimestamps();
    }

    public function questions() //: BelongsToMany
    {
//        return $this->belongsToMany(Mocktest::class,'course_mocktest')->withPivot('status')->withTimestamps();

        return $this->belongsToMany(Question::class, 'mocktest_questions', 'mocktest_id', 'question_id')->withPivot('user_id')->withTimestamps();
    }


    public function exminers(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'mocktest_questions', 'mocktest_id', 'user_id')->withTimestamps();
    }

    public function attendQuestions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'attend_mocktest_questions', 'mocktest_id', 'user_id')->withTimestamps();
    }


}
