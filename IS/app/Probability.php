<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Probability extends Model
{
    protected $table = 'probabilities';

    protected $fillable = ['day', 'i', 'j', 'probability'];
}
