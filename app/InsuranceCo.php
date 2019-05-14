<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InsuranceCo extends Model
{
    protected $fillable = [
        'name',
        // add all other fields
    ];
    
    protected $table = 'insurancecos';

    public function item() {
        return $this->hasMany('App\Item');
    }
}
