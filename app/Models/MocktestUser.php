<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MocktestUser extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'mocktest_user';



    public function mocktest(): BelongsTo
    {
        return $this->belongsTo(Mocktest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function givenAnswers(){
        return $this->hasMany(GivenMocktestAnswer::class);
    }

}
