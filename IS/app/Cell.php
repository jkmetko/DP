<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cell extends Model
{
    protected $table = 'Cells';

    public function measurements() {
        return $this->hasMany(Measurement::class);
    }

    public function pattern() {
        return $this->belongsTo(Pattern::class);
    }

}
