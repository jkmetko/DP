<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    protected $table = 'predictions';
    public $timestamps = false;

    protected $fillable = ['real_value', 'forecasted', 'ploted'];
}
