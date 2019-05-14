<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Responsible extends Model
{
    protected $fillable = [
        'name',
        'email',
    ];
    
    protected $table = 'responsibles';

    public function item() {
        return $this->hasMany('App\Item');
    }
}
