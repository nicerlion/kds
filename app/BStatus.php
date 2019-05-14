<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BStatus extends Model
{
    protected $fillable = [
        'name',
        // add all other fields
    ];
    protected $table = 'bs';

    public function item() {
        return $this->hasMany('App\Item');
    }
}
