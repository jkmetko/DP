<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Multiplicity extends Model
{
    protected $table = 'multiplicities';

    protected $fillable = ['day', 'i', 'j', 'multiplicity'];
}
