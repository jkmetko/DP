<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    protected $table = 'measurements';

    protected $fillable = ['date', 'time', 'consumption'];

    public function cell() {
        return $this->belongsTo(Cell::class);
    }

}

