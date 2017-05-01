<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Error extends Model
{
    protected $table = 'errors';

    protected $fillable = ['real_value', 'forecasted'];
}
