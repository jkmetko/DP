<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pattern extends Model
{
    protected $table = 'Patterns';

    protected $fillable = ['name', 'day'];

    public function cells() {
        return $this->hasMany(Cell::class);
    }

}

