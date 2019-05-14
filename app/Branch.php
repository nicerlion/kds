<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'sarlaf',
        // add all other fields
    ];
    protected $table = 'branches';

    public function item() {
        return $this->hasMany('App\Item');
    }
}
