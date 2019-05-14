<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $fillable = [
        'user_id',
        'comments',
        'model',
        'model_id',
    ];

    protected $table = 'records';
}
