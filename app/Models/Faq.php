<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $validate)
 */
class Faq extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

}
