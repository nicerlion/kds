<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IuserType extends Model
{
    protected $table = 'iuser_types';

    protected $fillable = [
        'name',
    ];

    public function iusers()
    {
        return $this->belongsToMany('App\Iuser', 'iuser_iuser_type', 'iuser_id', 'iuser_type_id');
    }
}
