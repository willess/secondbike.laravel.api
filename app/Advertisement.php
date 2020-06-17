<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'title',
        'detail'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
