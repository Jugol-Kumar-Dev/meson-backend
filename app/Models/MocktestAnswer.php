<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MocktestAnswer extends Model
{
    use HasFactory;

    protected $table = 'mocktest_answer';

    public function mocktest(): BelongsTo
    {
        return $this->belongsTo(Mocktest::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

}
