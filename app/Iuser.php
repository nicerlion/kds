<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Iuser extends Model
{
    protected $fillable = [
        'id',
        'name',
        'document',
        'sarlaf',
        'sarlaf_duedate',
        'sarlaf_expired',
        'image_path'
    ];

    public function items() {
        return $this->belongsToMany('App\Item');
    }

    public function iusertypes()
    {
        return $this->belongsToMany('App\IuserType', 'iuser_iuser_type', 'iuser_id', 'iuser_type_id');
    }

    
}
