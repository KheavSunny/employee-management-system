<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'city';

    protected $guarded = [];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
